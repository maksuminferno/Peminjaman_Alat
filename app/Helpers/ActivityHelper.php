<?php

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

/**
 * Log user activity
 * 
 * @param string $aktivitas Activity description
 * @return void
 */
function logActivity($aktivitas)
{
    try {
        LogAktivitas::create([
            'id_user' => Auth::id(),
            'aktivitas' => $aktivitas,
            'waktu' => now(),
        ]);
    } catch (\Exception $e) {
        // Fail silently to avoid breaking the application
        \Log::error('Failed to log activity: ' . $e->getMessage());
    }
}
