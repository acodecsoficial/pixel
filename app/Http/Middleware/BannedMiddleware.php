<?php

namespace App\Http\Middleware;

use Closure;

class BannedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            auth()->check() &&
            !is_null(auth()->user()->deleted_at)
        ) {
            auth()->logout();
            return response()->json([ 'error' => 'Account not found' ], 403);
        }

        return $next($request);
    }
}
