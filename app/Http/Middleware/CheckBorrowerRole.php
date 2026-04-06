<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBorrowerRole
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
        \Log::info('CheckBorrowerRole - User role: ' . $roleValue . ', User ID: ' . ($user->id_user ?? $user->id ?? 'unknown'));

        // Check for various possible borrower role values
        $isBorrower = in_array($roleValue, ['peminjam', 'borrower', 'user', 'pengguna']);

        if (!$isBorrower) {
            abort(403, 'Unauthorized access. This section is for borrowers only. Your role: ' . $roleValue);
        }

        return $next($request);
    }
}