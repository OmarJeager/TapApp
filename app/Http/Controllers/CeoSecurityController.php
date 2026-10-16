<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CeoSecurityController extends Controller
{
    /* ------------------------------------------------------------------
     | Shared query builders
     |------------------------------------------------------------------ */

    private function activityQuery(Request $request)
    {
        return UserActivityLog::with(['actor:id,name,email', 'target:id,name,email'])
            ->when($request->filled('event'), fn ($q) => $q->where('event', $request->string('event')->toString()))
            ->when($request->filled('user'), function ($q) use ($request) {
                $id = (int) $request->input('user');
                $q->where(fn ($sub) => $sub->where('actor_user_id', $id)->orWhere('target_user_id', $id));
            })
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to')));
    }

    private function userQuery(Request $request)
    {
        return User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%"));
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->input('role')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'suspended', fn ($q) => $q->where('suspended_until', '>', now()));
    }

    /* ------------------------------------------------------------------
     | Pages
     |------------------------------------------------------------------ */

    public function activity(Request $request)
    {
        $logs = $this->activityQuery($request)
            ->latest('created_at')
            ->paginate(30)
            ->withQueryString();

        $events = UserActivityLog::query()->select('event')->distinct()->orderBy('event')->pluck('event');
        $todayCount = UserActivityLog::whereDate('created_at', today())->count();

        return view('ceo.security.activity', compact('logs', 'events', 'todayCount'));
    }

    public function users(Request $request)
    {
        $users = $this->userQuery($request)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = User::query()->select('role')->distinct()->orderBy('role')->pluck('role');

        $stats = [
            'total'     => User::count(),
            'active'    => User::where('is_active', true)->count(),
            'inactive'  => User::where('is_active', false)->count(),
            'suspended' => User::where('suspended_until', '>', now())->count(),
        ];

        return view('ceo.security.users', compact('users', 'roles', 'stats'));
    }

    public function showUser(User $user)
    {
        $activities = UserActivityLog::where(function ($q) use ($user) {
            $q->where('actor_user_id', $user->id)->orWhere('target_user_id', $user->id);
        })->latest('created_at')->limit(12)->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'matricule' => $user->matricule,
                'role' => $user->role,
                'is_active' => (bool) $user->is_active,
                'profile_picture_url' => $user->profile_picture ? asset('storage/' . $user->profile_picture) : null,
                'last_ip_address' => $user->last_ip_address,
                'last_login_at' => $user->last_login_at?->toDateTimeString(),
                'last_seen_at' => $user->last_seen_at?->toDateTimeString(),
                'suspension_reason' => $user->suspension_reason,
                'suspended_until' => $user->suspended_until?->toDateTimeString(),
            ],
            'activities' => $activities->map(fn ($log) => [
                'event' => $log->event,
                'description' => $log->description,
                'ip_address' => $log->ip_address,
                'created_at' => $log->created_at?->toDateTimeString(),
            ]),
        ]);
    }

    /* ------------------------------------------------------------------
     | Actions
     |------------------------------------------------------------------ */

    public function forceLogout(User $user)
    {
        abort_if($user->id === Auth::id(), 422, 'You cannot revoke your own session from this action.');

        DB::transaction(function () use ($user) {
            $user->increment('session_version');
            UserActivityLog::record('user.force_logout', 'CEO revoked all sessions for this user.', $user->id);
        });

        return back()->with('success', "All sessions for {$user->name} have been revoked.");
    }

    public function suspend(Request $request, User $user)
    {
        abort_if($user->id === Auth::id(), 422, 'You cannot suspend your own account.');

        $validated = $request->validate([
            'suspension_reason' => ['required', 'string', 'min:5', 'max:2000'],
            'suspended_until' => ['required', 'date', 'after:now'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->forceFill([
                'suspension_reason' => $validated['suspension_reason'],
                'suspended_until' => $validated['suspended_until'],
                'session_version' => $user->session_version + 1,
            ])->save();

            UserActivityLog::record('user.suspended', 'Account suspended until ' . $validated['suspended_until'], $user->id);
        });

        return back()->with('success', "Suspension applied to {$user->name}.");
    }

    public function unsuspend(User $user)
    {
        $user->forceFill(['suspension_reason' => null, 'suspended_until' => null])->save();
        UserActivityLog::record('user.unsuspended', 'CEO removed account suspension.', $user->id);

        return back()->with('success', "Suspension removed for {$user->name}.");
    }

    /* ------------------------------------------------------------------
     | Exports
     |------------------------------------------------------------------ */

    public function exportUsers(Request $request): StreamedResponse
    {
        $query = $this->userQuery($request);

        UserActivityLog::record('users.csv_exported', 'CEO exported user directory.', null, [
            'filters' => $request->only(['search', 'role', 'status']),
        ]);

        $filename = 'tapapp-users-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Matricule', 'Email', 'Role', 'Active', 'Last IP', 'Last Login', 'Last Seen', 'Created At']);

            $query->orderBy('id')->chunk(500, function ($users) use ($out) {
                foreach ($users as $user) {
                    fputcsv($out, [
                        $user->id, $user->name, $user->matricule, $user->email, $user->role,
                        $user->is_active ? 'Yes' : 'No', $user->last_ip_address,
                        $user->last_login_at?->toDateTimeString(),
                        $user->last_seen_at?->toDateTimeString(),
                        $user->created_at?->toDateTimeString(),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportActivity(Request $request): StreamedResponse
    {
        // chunk() needs a stable order, so use chunkById-friendly ordering on id
        $logs = $this->activityQuery($request)->orderByDesc('id');

        UserActivityLog::record('activity.csv_exported', 'CEO exported activity log.', null, [
            'filters' => $request->only(['event', 'user', 'from', 'to']),
        ]);

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Timestamp', 'Actor', 'Target', 'Event', 'Description', 'IP', 'Method', 'Route']);

            $logs->chunk(500, function ($items) use ($out) {
                foreach ($items as $log) {
                    fputcsv($out, [
                        $log->created_at?->toDateTimeString(),
                        $log->actor?->name,
                        $log->target?->name,
                        $log->event,
                        $log->description,
                        $log->ip_address,
                        $log->method,
                        $log->route_name,
                    ]);
                }
            });
            fclose($out);
        }, 'tapapp-activity-' . now()->format('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}