<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StoreUserLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return $next($request);
        }

        $user->last_activity_at = Carbon::now();
        $user->save();

        return $next($request);
    }
}
