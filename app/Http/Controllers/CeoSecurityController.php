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
    /* ==================================================================
     |  QUERY BUILDERS
     |================================================================== */

    private function activityQuery(Request $request)
    {
        return UserActivityLog::with(['actor:id,name,email', 'target:id,name,email'])
            ->when($request->filled('event'), fn ($q) => $q->where('event', $request->string('event')->toString()))
            ->when($request->filled('severity'), fn ($q) => $q->where('severity', $request->input('severity')))
            ->when($request->filled('user'), function ($q) use ($request) {
                $id = (int) $request->input('user');
                $q->where(fn ($sub) => $sub->where('actor_user_id', $id)->orWhere('target_user_id', $id));
            })
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'),   fn ($q) => $q->whereDate('created_at', '<=', $request->input('to')));
    }

    private function userQuery(Request $request)
    {
        return User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->input('search');
                $q->where(fn ($sub) => $sub->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('matricule', 'like', "%{$s}%")
                    ->orWhere('role', 'like', "%{$s}%"));
            })
            ->when($request->filled('role'),   fn ($q) => $q->where('role', $request->input('role')))
            ->when($request->input('status') === 'active',    fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive',  fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'suspended', fn ($q) => $q->where('suspended_until', '>', now()));
    }

    /* ==================================================================
     |  PAGES
     |================================================================== */

    public function activity(Request $request)
    {
        $logs = $this->activityQuery($request)
            ->latest('created_at')
            ->paginate(30)
            ->withQueryString();

        $events     = UserActivityLog::query()->select('event')->distinct()->orderBy('event')->pluck('event');
        $todayCount = UserActivityLog::whereDate('created_at', today())->count();

        $severityCounts = UserActivityLog::query()
            ->selectRaw('severity, COUNT(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity')
            ->all();

        return view('ceo.security.activity', compact('logs', 'events', 'todayCount', 'severityCounts'));
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
            'online'    => User::where('last_seen_at', '>=', now()->subMinutes(5))->count(),
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
                'last_activity_at' => $user->last_activity_at?->toDateTimeString(),
                'session_version' => (int) $user->session_version,
                'suspension_reason' => $user->suspension_reason,
                'suspended_until' => $user->suspended_until?->toDateTimeString(),
            ],
            'activities' => $activities->map(fn ($log) => [
                'event' => $log->event,
                'description' => $log->description,
                'ip_address' => $log->ip_address,
                'severity' => $log->severity,
                'created_at' => $log->created_at?->toDateTimeString(),
            ]),
        ]);
    }

    /* ==================================================================
     |  ACTIONS
     |================================================================== */

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
            'suspended_until'   => ['required', 'date', 'after:now'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->forceFill([
                'suspension_reason' => $validated['suspension_reason'],
                'suspended_until'   => $validated['suspended_until'],
                'session_version'   => $user->session_version + 1,
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

    /* ==================================================================
     |  PRESENCE / LIVE
     |================================================================== */

    public function presence(): \Illuminate\Http\JsonResponse
    {
        $rows = User::query()
            ->select(['id', 'name', 'last_seen_at', 'last_activity_at', 'is_active', 'suspended_until'])
            ->where(function ($q) {
                $q->where('last_seen_at', '>=', now()->subMinutes(30))
                  ->orWhere('last_activity_at', '>=', now()->subMinutes(30));
            })
            ->orderByDesc('last_seen_at')
            ->limit(40)
            ->get()
            ->mapWithKeys(function (User $u) {
                $suspended = $u->suspended_until && $u->suspended_until->isFuture();
                $status = $suspended ? 'suspended' : ($u->is_online ? 'online' : 'away');

                return [$u->id => [
                    'name'        => $u->name,
                    'status'      => $status,
                    'last_seen'   => $u->last_seen_at?->diffForHumans(),
                    'last_action' => $u->last_activity_at?->diffForHumans(),
                ]];
            });

        return response()->json($rows);
    }

    public function feed(Request $request): \Illuminate\Http\JsonResponse
    {
        $afterId = (int) $request->input('after', 0);

        $logs = $this->activityQuery($request)
            ->where('id', '>', $afterId)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn (UserActivityLog $log) => [
                'id'            => $log->id,
                'event'         => $log->event,
                'severity'      => $log->severity,
                'description'   => $log->description,
                'actor'         => $log->actor?->name ?? $log->actor_name ?? 'System',
                'target'        => $log->target?->name ?? $log->target_name,
                'target_id'     => $log->target_user_id,
                'ip'            => $log->ip_address,
                'method'        => $log->method,
                'route'         => $log->route_name,
                'created_at'    => $log->created_at?->format('d M Y · H:i:s'),
                'created_human' => $log->created_at?->diffForHumans(),
            ]);

        return response()->json([
            'latest_id' => $logs->max('id') ?? $afterId,
            'logs'      => $logs,
        ]);
    }

    public function heatmap(Request $request): \Illuminate\Http\JsonResponse
    {
        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to   = $request->input('to',   now()->toDateString());

        $driver = DB::connection()->getDriverName();

        $dowExpr  = $driver === 'pgsql' ? 'EXTRACT(DOW FROM created_at) + 1' : 'DAYOFWEEK(created_at)';
        $hourExpr = $driver === 'pgsql' ? 'EXTRACT(HOUR FROM created_at)'    : 'HOUR(created_at)';

        $rows = UserActivityLog::query()
            ->selectRaw("{$dowExpr} as dow, {$hourExpr} as hour, COUNT(*) as total")
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->groupBy('dow', 'hour')
            ->get();

        $matrix = collect(range(1, 7))->mapWithKeys(fn ($d) => [$d => array_fill(0, 24, 0)])->all();

        foreach ($rows as $r) {
            $matrix[(int) $r->dow][(int) $r->hour] = (int) $r->total;
        }

        return response()->json([
            'from'   => $from,
            'to'     => $to,
            'matrix' => $matrix,
            'max'    => max(array_map('max', $matrix)) ?: 1,
        ]);
    }

    /* ==================================================================
     |  EXPORTS
     |================================================================== */

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
        $logs = $this->activityQuery($request)->orderByDesc('id');

        UserActivityLog::record('activity.csv_exported', 'CEO exported activity log.', null, [
            'filters' => $request->only(['event', 'user', 'from', 'to', 'severity']),
        ]);

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Timestamp', 'Severity', 'Actor', 'Target', 'Event', 'Description', 'IP', 'Method', 'Route']);

            $logs->chunk(500, function ($items) use ($out) {
                foreach ($items as $log) {
                    fputcsv($out, [
                        $log->created_at?->toDateTimeString(),
                        $log->severity,
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
