<?php

namespace App\Http\Middleware;

use App\Strategies\JwtCookieTokenValidationStrategy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Strategies\TokenValidationStrategy;

class UserMiddleware
{
    private $tokenValidationStrategy;

    public function __construct(JwtCookieTokenValidationStrategy $tokenValidationStrategy)
    {
        $this->tokenValidationStrategy = $tokenValidationStrategy;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $validationResult = $this->tokenValidationStrategy->validate($request);

        if ($validationResult['status'] === 'error') {
            return redirect()->route('login')->with('message', 'You need to be logged in to access this page');
        }

        $decoded = $validationResult['decoded'];
        $userId = $decoded->sub;

        $request->merge(['user_id' => $userId]);
        return $next($request);
    }
}