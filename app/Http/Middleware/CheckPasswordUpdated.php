<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPasswordUpdated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // If password has not been updated, redirect to change-password page
            if (!$user->is_password_update && !$request->routeIs('update-password') && !$request->routeIs('password-update')) {
                return redirect()->route('update-password');
            }
        }

        return $next($request);
    }
}
