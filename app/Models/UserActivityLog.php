<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class UserActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'actor_user_id', 'target_user_id', 'event', 'description',
        'ip_address', 'method', 'route_name', 'metadata', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

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

        return static::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $targetUserId,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request->ip(),
            'method' => $request->method(),
            'route_name' => optional($request->route())->getName(),
            'metadata' => $safeMetadata ?: null,
            'created_at' => now(),
        ]);
    }
}
