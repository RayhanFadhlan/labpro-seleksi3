<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Log;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        View::composer('layouts.app', function ($view) {
            Log::info('Endpoint accessed: layouts.app');
            $authToken = Cookie::get('auth_token');
            $user = null;

            if ($authToken) {
                try {
                    $decoded = JWT::decode($authToken, new Key(env('JWT_SECRET'), 'HS256'));
                    $userId = $decoded->sub;
                    $user = User::find($userId);
                } catch (\Exception $e) {
                    $view->with('user', null);
                }
            }

            $view->with('user', $user);
        });
    }
}