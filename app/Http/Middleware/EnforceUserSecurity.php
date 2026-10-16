<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceUserSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = $request->user();
            $capturedVersion = $request->session()->get('tapapp_session_version');

            // A missing version is treated as an old/untrusted session; login must set it.
            if ($capturedVersion === null || (int) $capturedVersion !== (int) $user->session_version) {
                UserActivityLog::record('session.revoked', 'Session ended because its version is no longer valid.', $user->id);
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your session has expired or was revoked. Please sign in again.']);
            }

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'This account is inactive. Contact your administrator.']);
            }

            if ($user->suspended_until && $user->suspended_until->isFuture()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'This account is temporarily suspended.']);
            }

            if ($user->locked_until && $user->locked_until->isFuture()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Too many failed login attempts. Try again later.']);
            }

            // Audit authenticated state-changing requests. Do not store form values.
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
                && ! $request->routeIs('activity.track')) {
                UserActivityLog::record(
                    'request.mutating',
                    'Authenticated state-changing request.',
                    null,
                    [
                        'path' => substr($request->path(), 0, 180),
                        'status_context' => 'request_started',
                    ],
                    $request
                );
            }
        }

        return $next($request);
    }
}
