<?php
namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        // Kebijakan password: minimal 8 karakter, wajib huruf besar, huruf kecil, dan angka.
        // Produksi selalu HTTPS: semua URL (asset, route) dibuat dengan skema https.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers());
    }
}
