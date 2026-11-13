<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan HTTP security headers pada setiap respons dinamis aplikasi.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Sumber eksternal yang memang dipakai aplikasi (CDN Bootstrap, jQuery, DataTables,
        // SweetAlert2, Chart.js, Font Awesome, Google Fonts) dan embed Google Maps di beranda.
        $cdn = 'https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.datatables.net https://code.jquery.com';

        $csp = implode('; ', [
            "default-src 'self'",
            // 'unsafe-inline' masih dibutuhkan karena view memakai <script>/onclick inline.
            // Rencana perbaikan: pindahkan ke file JS terpisah lalu pakai nonce.
            "script-src 'self' 'unsafe-inline' $cdn",
            "style-src 'self' 'unsafe-inline' $cdn https://fonts.googleapis.com",
            "font-src 'self' data: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.gstatic.com",
            "img-src 'self' data: blob:",
            "connect-src 'self'",
            "frame-src https://www.google.com https://maps.google.com",
            "frame-ancestors 'self'",   // anti-clickjacking
            "base-uri 'self'",
            "form-action 'self'",
            "object-src 'none'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // HSTS hanya dikirim lewat HTTPS (di localhost/HTTP biasa tidak berguna).
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        // Jangan membocorkan versi PHP.
        header_remove('X-Powered-By');

        return $response;
    }
}
