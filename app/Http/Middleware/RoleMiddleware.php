<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            abort(403);
        }

        if ($request->user()->role !== $role) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You are not allowed to access this page.');
        }

        return $next($request);
    }
}
