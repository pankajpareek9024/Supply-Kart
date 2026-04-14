<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * - Redirects unauthenticated users to admin login.
     * - Auto-logs-out admins who have been deactivated mid-session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Not logged in at all
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        // Logged in but account was deactivated mid-session
        $admin = Auth::guard('admin')->user();
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account has been deactivated.'], 403);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated. Please contact the super admin.');
        }

        return $next($request);
    }
}
