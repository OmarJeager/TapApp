<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable(['name', 'matricule', 'email', 'password', 'profile_picture', 'signature', 'role','is_active','last_ip_address','last_login_at','last_seen_at','suspended_until','suspension_reason','failed_login_attempts','locked_until','session_version'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at'     => 'datetime',
            'last_seen_at'      => 'datetime',
            'suspended_until' => 'datetime',
            'locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
            'session_version' => 'integer',
        ];
    }
    /**
     * Consider a user "online" if seen within the last 5 minutes.
     */
    public const ONLINE_WINDOW_MINUTES = 5;

    public function getIsOnlineAttribute(): bool
    {
        if (! $this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at
            ->greaterThan(now()->subMinutes(self::ONLINE_WINDOW_MINUTES));
    }

    public function getLastSeenHumanAttribute(): string
    {
        if (! $this->last_seen_at) {
            return __('Never connected');
        }

        return $this->last_seen_at->diffForHumans();
    }

    public function getLastLoginHumanAttribute(): string
    {
        if (! $this->last_login_at) {
            return __('Never connected');
        }

        return $this->last_login_at->diffForHumans();
    }
}
