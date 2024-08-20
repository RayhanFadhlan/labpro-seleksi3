<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

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
        // Use view composers to share data with specific views
        View::composer('layouts.app', function ($view) {
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