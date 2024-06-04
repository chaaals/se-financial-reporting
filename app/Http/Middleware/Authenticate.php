<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $env = env('APP_ENV', 'local');
        $debug = env('APP_DEBUG');

        $route = $env == 'local' ? 'login' : 'test';

        if($env == 'production' && !$debug){
            $userId = Session::get('user_id');
            $password = Session::get('password');
            $roleId = Session::get('role_id');

            if((!$userId && !$password) || ($roleId != 11 || $roleId !== 12)){
                $route = 'access-denied';
            } else {
                Auth::attempt([$userId, $password]);
            }
        }

        return $request->expectsJson() ? null : route($route);
    }
}
