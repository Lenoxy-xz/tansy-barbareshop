<?php
namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
class RouteServiceProvider extends ServiceProvider {
    public const HOME = '/';
    public function boot(): void {
        $this->configureRateLimiting();
        $this->routes(function () {
            Route::middleware('api')->prefix('api')->group(base_path('routes/api.php'));
            Route::middleware('web')->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void {
        // Login: maks. 5 percobaan/menit untuk kombinasi username + IP,
        // dan maks. 20/menit per IP (mencegah menebak banyak username dari satu sumber).
        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(5)->by(strtolower((string) $request->input('username')).'|'.$request->ip()),
                Limit::perMinute(20)->by($request->ip()),
            ];
        });

        // Registrasi: maks. 10 akun baru per jam per IP (mencegah pembuatan akun massal).
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(10)->by($request->ip());
        });
    }
}
