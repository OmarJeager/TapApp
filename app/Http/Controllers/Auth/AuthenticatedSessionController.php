<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate a user without using LoginRequest.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim($credentials['email']));
        $rateLimitKey = 'login:' . $email . '|' . $request->ip();

        // Rate limit login attempts, including attempts for unknown emails.
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Too many login attempts. Please try again in '
                        . RateLimiter::availableIn($rateLimitKey)
                        . ' seconds.',
                ]);
        }

        $userRecord = User::where('email', $email)->first();

        // Reject a currently locked account.
        if (
            $userRecord &&
            $userRecord->locked_until &&
            $userRecord->locked_until->isFuture()
        ) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Your account is temporarily locked. Please try again later.',
                ]);
        }

        // Authenticate using Laravel's session guard.
        if (! Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            RateLimiter::hit($rateLimitKey, 900);

            if ($userRecord) {
                $attempts = min(
                    255,
                    (int) $userRecord->failed_login_attempts + 1
                );

                $updates = [
                    'failed_login_attempts' => $attempts,
                ];

                if ($attempts >= 5) {
                    $updates['locked_until'] = now()->addMinutes(15);
                }

                $userRecord->forceFill($updates)->save();

                $this->recordActivity(
                    $request,
                    'auth.login_failed',
                    'Failed login attempt.',
                    $userRecord->id
                );
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'The provided credentials are incorrect, or access is temporarily unavailable.',
                ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Reject disabled accounts even if the password is correct.
        $isSuspended = $user->suspended_until
            && $user->suspended_until->isFuture();

        if (! $user->is_active || $isSuspended) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Your account is inactive or suspended. Contact your administrator.',
                ]);
        }

        // Successful authentication.
        RateLimiter::clear($rateLimitKey);

        $request->session()->regenerate();

        $user->forceFill([
            'last_ip_address' => $request->ip(),
            'last_login_at' => now(),
            'last_seen_at' => now(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        // Used by EnforceUserSecurity to detect forced logouts.
        $request->session()->put(
            'tapapp_session_version',
            (int) $user->session_version
        );

        $this->recordActivity(
            $request,
            'auth.login',
            'User logged in successfully.',
            $user->id
        );

        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            'quality' => redirect()->route('quality.dashboard'),
            'ceo' => redirect()->route('ceo.dashboard'),
            default => redirect()->route('role.error'),
        };
    }

    /**
     * Log out the authenticated user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $this->recordActivity(
                $request,
                'auth.logout',
                'User logged out.',
                $user->id
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Store a security activity entry.
     */
    private function recordActivity(
        Request $request,
        string $event,
        string $description,
        ?int $targetUserId = null
    ): void {
        // Keep login functional if the activity table is not migrated yet.
        if (! \Illuminate\Support\Facades\Schema::hasTable('user_activity_logs')) {
            return;
        }

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'target_user_id' => $targetUserId,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'route_name' => $request->route()?->getName(),
            'metadata' => [],
        ]);
    }
}
