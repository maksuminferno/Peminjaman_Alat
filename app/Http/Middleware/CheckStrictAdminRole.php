<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStrictAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check for different possible field names for role
        $roleValue = strtolower($user->role ?? $user->jenis_role ?? $user->user_role ?? $user->user_type ?? 'unknown');

        // Debug: Log the actual role value for troubleshooting
        \Log::info('CheckStrictAdminRole - User role: ' . $roleValue . ', User ID: ' . ($user->id_user ?? $user->id ?? 'unknown'));

        // Check for strict admin role values (excluding petugas)
        $isStrictAdmin = in_array($roleValue, ['admin', 'administrator', 'superadmin']);

        if (!$isStrictAdmin) {
            abort(403, 'Unauthorized access. This section is for strict administrators only. Your role: ' . $roleValue);
        }

        return $next($request);
    }
}