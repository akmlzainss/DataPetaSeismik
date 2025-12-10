# Instalasi Package untuk Export Excel dan PDF

Untuk mengaktifkan fitur export Excel dan PDF, Anda perlu menginstal package berikut:

## 1. Install Package via Composer

```bash
composer require phpoffice/phpspreadsheet:^2.0
composer require barryvdh/laravel-dompdf:^3.0
```

## 2. Publish Config (Opsional untuk DomPDF)

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## 3. Konfigurasi DomPDF (Opsional)

Edit file `config/dompdf.php` jika diperlukan untuk mengatur:

-   Font path
-   Paper size default
-   Orientation
-   dll

## 4. Pastikan Logo Tersedia

Pastikan file logo ada di `public/storage/logo-esdm2.png`

## 5. Test Export

Setelah instalasi, test fitur export di:

-   Dashboard Admin → Laporan
-   Klik tombol "Export Excel" atau "Export PDF"

## Troubleshooting

### Jika ada error "Class not found":

```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Jika logo tidak muncul di PDF:

-   Pastikan path logo benar
-   Cek permission file logo
-   Gunakan absolute path jika diperlukan

### Jika export Excel lambat:

-   Batasi jumlah data yang di-export
-   Gunakan pagination untuk data besar
-   Pertimbangkan export background job untuk data sangat besar

## Fitur Export yang Tersedia

### Excel Export:

-   Header perusahaan dengan logo
-   Statistik ringkasan
-   Data survei lengkap dengan formatting
-   Auto-width columns
-   Professional styling

### PDF Export:

-   Template surat resmi
-   Logo perusahaan
-   Statistik visual
-   Pagination otomatis (25 data per halaman)
-   Maksimal 100 data untuk performa optimal

## Catatan Penting

1. **Memory Limit**: Export data besar membutuhkan memory yang cukup
2. **Timeout**: Set timeout yang sesuai untuk export data besar
3. **Storage**: Pastikan ada space yang cukup untuk file temporary
4. **Permissions**: Pastikan Laravel bisa write ke storage folder
