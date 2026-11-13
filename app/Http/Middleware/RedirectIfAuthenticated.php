<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class RedirectIfAuthenticated {
    public function handle(Request $request, Closure $next, string ...$guards): Response {
        foreach (empty($guards) ? [null] : $guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                return redirect($user->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard'));
            }
        }
        return $next($request);
    }
}
