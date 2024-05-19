<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $env = env('APP_ENV');
        $debug = env('APP_DEBUG');

        $route = 'login';

        if($env == 'production' && !$debug){
            $route = 'access-denied';
        }
        
        return $request->expectsJson() ? null : route($route);
    }
}
