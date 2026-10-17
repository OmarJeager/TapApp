<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class UserActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'actor_user_id', 'actor_name', 'target_user_id', 'target_name',
        'event', 'severity', 'description',
        'ip_address', 'user_agent', 'method', 'route_name', 'metadata', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata'   => 'array',
            'created_at' => 'datetime',
        ];
    }

    /* ----------------------------- Relations ----------------------------- */

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /* ----------------------------- Severity ----------------------------- */

    public const SEVERITIES = ['info', 'notice', 'warning', 'critical'];

    public static function severityFor(string $event): string
    {
        return match (true) {
            str_contains($event, 'suspend'),
            str_contains($event, 'force_logout'),
            str_contains($event, 'delete'),
            str_contains($event, 'failed_login'),
            str_contains($event, 'locked')          => 'critical',

            str_contains($event, 'logout'),
            str_contains($event, 'unsuspend'),
            str_contains($event, 'export'),
            str_contains($event, 'import')          => 'warning',

            str_contains($event, 'login'),
            str_contains($event, 'create'),
            str_contains($event, 'update')          => 'notice',

            default                                  => 'info',
        };
    }

    public function getSeverityToneAttribute(): string
    {
        return $this->severity ?: 'info';
    }

    /* ----------------------------- Recorder ----------------------------- */

    public static function record(
        string $event,
        ?string $description = null,
        ?int $targetUserId = null,
        array $metadata = [],
        ?\Illuminate\Http\Request $request = null
    ): self {
        $request ??= request();

        $safeMetadata = collect($metadata)->except([
            'password', 'password_confirmation', 'current_password',
            'token', 'security_code', '_token', 'remember_token',
        ])->all();

        $actor  = Auth::user();
        $target = $targetUserId ? User::find($targetUserId) : null;

        return static::create([
            'actor_user_id'  => $actor?->id,
            'actor_name'     => $actor?->name,
            'target_user_id' => $targetUserId,
            'target_name'    => $target?->name,
            'event'          => $event,
            'severity'       => self::severityFor($event),
            'description'    => $description,
            'ip_address'     => $request->ip(),
            'user_agent'     => substr((string) $request->userAgent(), 0, 500),
            'method'         => $request->method(),
            'route_name'     => optional($request->route())->getName(),
            'metadata'       => $safeMetadata ?: null,
            'created_at'     => now(),
        ]);
    }

    /* ------------------------------ Scopes ------------------------------ */

    public function scopeActedBy($q, int $id)      { return $q->where('actor_user_id', $id); }
    public function scopeTargeting($q, int $id)    { return $q->where('target_user_id', $id); }
    public function scopeSeverityIn($q, array $s)  { return $q->whereIn('severity', $s); }

    public function scopeBetween($q, ?string $from, ?string $to)
    {
        return $q
            ->when($from, fn ($x) => $x->whereDate('created_at', '>=', $from))
            ->when($to,   fn ($x) => $x->whereDate('created_at', '<=', $to));
    }
}
