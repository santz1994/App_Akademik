Jalankan migration: php artisan migrate untuk memastikan semua kolom ada
Clear cache: php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan config:clear
Jalankan fix transaksi: php artisan fix:transaksi-kategori untuk mengisi kategori_kinerja_id yang NULL
Periksa versi PHP: Error log lama menunjukkan PHP 7.4, tapi dependency butuh PHP ≥ 8.4. Pastikan server pakai PHP 8.4+

# 1. Apply migrations (indexes)
php artisan migrate

# 2. Clear cache dengan aman
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# 3. Clean expired sessions
php artisan db:session-gc

# 4. RESTART MySQL service untuk reset aborted clients counter

php artisan migrate
php artisan cache:clear