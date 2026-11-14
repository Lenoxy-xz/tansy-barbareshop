<?php
namespace App\Http\Middleware;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
class TrustProxies extends Middleware {
    // Aplikasi berjalan di balik reverse proxy platform hosting (Render): percayai header X-Forwarded-*
    // agar HTTPS, IP pengunjung (rate limiting), dan URL terdeteksi dengan benar.
    protected $proxies = '*';
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB;
}
