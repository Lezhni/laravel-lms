<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class ForceJsonResponse
 * @package App\Http\Middleware
 */
class ForceJsonResponse
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}