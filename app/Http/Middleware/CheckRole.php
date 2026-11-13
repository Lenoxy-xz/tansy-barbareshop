<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckRole {
    public function handle(Request $request, Closure $next, string $role): Response {
        if (!auth()->check()) return redirect()->route('login');
        if (auth()->user()->role !== $role) {
            return redirect(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard'));
        }
        return $next($request);
    }
}
