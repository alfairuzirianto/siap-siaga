<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (! $user->is_active) {
            abort(403, 'Akun anda tidak aktif. Hubungi administrator.');
        }

        if (! $user->hasRole(...$roles)) {
            abort(403, 'Akun anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
