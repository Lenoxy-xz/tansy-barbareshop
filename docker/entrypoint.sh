#!/bin/sh
# Dijalankan setiap container start (Render).
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "ERROR: environment variable APP_KEY belum diisi." >&2
    exit 1
fi

# Folder yang harus ada dan dapat ditulis (disk container bersifat sementara)
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data \
         storage/logs storage/app/pembayaran bootstrap/cache \
         public/uploads/layanan public/uploads/profil public/uploads/pembayaran

# Buat/ubah struktur tabel, lalu isi data awal bila database masih kosong (seeder idempoten)
php artisan migrate --force
php artisan db:seed --force

chown -R www-data:www-data storage bootstrap/cache public/uploads

exec apache2-foreground
