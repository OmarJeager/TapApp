<?php
// app/Http/Middleware/UpdateLastSeen.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Throttle writes to once per minute to avoid DB hammering.
            $shouldUpdate = ! $user->last_seen_at
                || $user->last_seen_at->diffInSeconds(now()) >= 60;

            if ($shouldUpdate) {
                $user->forceFill([
                    'last_seen_at'    => now(),
                    'last_ip_address' => $request->ip(),
                ])->saveQuietly();
            }
        }

        return $next($request);
    }
}
