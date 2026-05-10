<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        \Log::info('Admin middleware check', [
            'user_exists' => $request->user() !== null,
            'user_id' => $request->user()?->id,
            'user_role_id' => $request->user()?->role_id,
            'is_admin' => $request->user() && (int) $request->user()->role_id === 1,
            'requested_path' => $request->path(),
            'session_id' => session()->getId()
        ]);

        if (! $request->user() || (int) $request->user()->role_id !== 1) {
            \Log::warning('Access denied', [
                'reason' => 'Not admin or no user',
                'user' => $request->user(),
                'role_id' => $request->user()?->role_id
            ]);
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
