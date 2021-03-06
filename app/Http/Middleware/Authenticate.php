<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            $response = Response::json(['message' => 'Требуется авторизация'], 401);
            abort($response);
        }
    }
}
