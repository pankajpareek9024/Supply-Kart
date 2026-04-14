<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery.login')->with('error', 'Please login to access the delivery panel.');
        }

        if (!Auth::guard('delivery_boy')->user()->is_active) {
            Auth::guard('delivery_boy')->logout();
            return redirect()->route('delivery.login')->with('error', 'Your account is deactivated. Please contact support.');
        }

        return $next($request);
    }
}
