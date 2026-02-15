# DOKUMENTASI TEKNIS SISTEM INFORMASI DATA PETA SEISMIK
## Dokumen Referensi Laporan PKL

Dokumen ini berisi rincian teknis mendalam mengenai sistem yang dibangun, mencakup arsitektur, database, fitur, dan implementasi kode. Gunakan poin-poin ini untuk menyusun **Bab 3 (Analisis Sistem)** dan **Bab 4 (Implementasi & Pembahasan)** pada laporan PKL.

---

## 1. IDENTITAS PROYEK

| Atribut | Keterangan |
|---------|------------|
| **Nama Sistem** | Sistem Informasi Data Peta Seismik / Survei Geologi Kelautan |
| **Pemilik** | BBSPGL (Balai Besar Survei dan Pemetaan Geologi Laut) |
| **Platform** | Web Application (Berbasis Website) |
| **Framework Utama** | Laravel 12.x (PHP 8.2+) |
| **Tujuan Utama** | Digitalisasi arsip survei seismik konvensional menjadi peta interaktif yang mudah diakses publik dan dikelola admin. |

---

## 2. ARSITEKTUR & TEKNOLOGI (BAB ANALISIS)

Bagian ini menjelaskan spesifikasi teknis yang digunakan dalam pembangunan sistem.

### A. Tech Stack (Teknologi)
1.  **Backend (Sisi Server)**:
    *   **Bahasa Pemrograman**: PHP 8.2+
    *   **Framework**: Laravel 12.0
    *   **Arsitektur**: MVC (Model-View-Controller)
    *   **Server Lokal**: Laragon (Apache/Nginx, MySQL)

2.  **Frontend (Sisi Pengguna)**:
    *   **Template Engine**: Blade Templates (Bawaan Laravel)
    *   **Styling**: Native CSS3 + CSS Variables (Modern Design)
    *   **Scripting**: Vanilla JavaScript (ES6)
    *   **Peta Interaktif**: Leaflet.js (Open Source Map Library)
    *   **Peta Dasar (Tiles)**: OpenStreetMap

3.  **Database**:
    *   **DBMS**: MySQL / MariaDB
    *   **ORM**: Eloquent ORM (Laravel)

4.  **Library Pendukung (Dependencies)**:
    *   `barryvdh/laravel-dompdf`: Untuk fitur cetak/export laporan ke PDF.
    *   `phpoffice/phpspreadsheet`: Untuk export data ke Excel (.xlsx).
    *   `intervention/image`: (Opsional) Untuk manipulasi/resize gambar upload.

---

## 3. STRUKTUR DATABASE MENDALAM (BAB PERANCANGAN)

Sistem menggunakan database relasional dengan tabel-tabel berikut.

### A. Tabel `admin`
Menyimpan data administrator yang memiliki akses penuh.
*   **Kolom Penting**: `nama`, `email` (unik), `kata_sandi` (terenkripsi Bcrypt).
*   **Fungsi**: Autentikasi masuk ke panel admin.

### B. Tabel `data_survei` (Core Data)
Menyimpan informasi detail hasil survei kelautan.
*   `judul` (String): Nama kegiatan survei.
*   `tahun` (Year): Tahun pelaksanaan.
*   `tipe` (String): Jenis survei (2D, 3D, High Resolution).
*   `wilayah` (String): Lokasi umum survei.
*   `deskripsi` (Text): Penjelasan detail teknis.
*   `gambar_pratinjau` (String): Path file gambar cover.
*   `file_scan_asli` (String): Path file dokumen asli (PDF/TIFF) *Secure*.
*   **Relasi**: `BelongsTo` Admin (diunggah_oleh).

### C. Tabel `grid_kotak` (Sistem Spasial)
Menyimpan data kotak-kotak (grid) pada peta.
*   `nomor_kotak` (String, Unique): ID kotak (misal: "1219").
*   `bounds_sw_lat`, `bounds_sw_lng`: Koordinat sudut Barat-Daya.
*   `bounds_ne_lat`, `bounds_ne_lng`: Koordinat sudut Timur-Laut.
*   `status`: 'empty' (abu-abu) atau 'filled' (kuning/merah jika ada data).
*   **Konsep**: Sistem tidak menggunakan titik (point) marker biasa, melainkan area segiempat (grid) untuk merepresentasikan cakupan wilayah.

### D. Tabel `grid_seismik` (Pivot Table / Jembatan)
Tabel penghubung Many-to-Many antara Data Survei dan Grid Kotak.
*   `grid_kotak_id`: ID kotak.
*   `data_survei_id`: ID survei.
*   **Logika**: Satu survei bisa mencakup banyak kotak grid (lintasan panjang), dan satu kotak grid bisa berisi banyak survei (tumpumpang tindih tahun berbeda).

### E. Tabel `pegawai_internal`
Untuk akses khusus pegawai internal BBSPGL.
*   `email` (Harus domain `@esdm.go.id`).
*   `is_approved`: Status persetujuan admin.
*   `verification_token`: Token untuk verifikasi email.

---

## 4. FITUR & FUNGSIONALITAS MENDALAM (BAB PEMBAHASAN SISTEM)

### Modul 1: Manajemen Peta & Grid (Fitur Unggulan)
*   **Cara Kerja**: Peta menampilkan grid kotak berdasarkan koordinat bounding box.
*   **Interaksi**:
    *   Saat grid diklik, sistem melakukan request AJAX (Asynchronous JavaScript) mengambil data survei yang terkait dengan grid tersebut.
    *   Data ditampilkan dalam Modal Popup tanpa reload halaman.
*   **Logika Warna**: Grid yang memiliki data (`total_data > 0`) diberi warna/opacity berbeda dengan grid kosong.

### Modul 2: Katalog & Pencarian
*   **Fitur**: Pencarian judul, filter berdasarkan Tahun, Tipe Survei, dan Wilayah.
*   **Teknis**: Menggunakan query `WHERE` dinamis pada Controller Laravel.
*   **Pagination**: Data ditampilkan per halaman untuk menjaga performa loading.

### Modul 3: Keamanan (Security Analysis)
1.  **Authentication Guards**: Memisahkan sesi login `admin` dan `pegawai`. Admin tidak bisa login di halaman pegawai, dan sebaliknya.
2.  **Protected Routes**: Middleware memastikan halaman admin tidak bisa diakses tanpa login.
3.  **Secure File Download**: File scan asli (`file_scan_asli`) tidak ditaruh di folder `public`. Disimpan di `storage/app` dan hanya bisa didownload melalui route khusus yang mengecek hak akses pegawai (`ScanDownloadController`).
4.  **CSRF Protection**: Semua form dilindungi token anti-CSRF.
5.  **SQL Injection Prevention**: Menggunakan Eloquent bindings untuk mencegah serangan injeksi SQL.

### Modul 4: Pelaporan (Reporting)
*   **PDF**: Admin bisa mencetak rekap data survei per halaman atau data terpilih. Menggunakan view blade khusus yang di-render menjadi PDF.
*   **Excel**: Export data untuk pengolahan lebih lanjut di spreadsheet.

---

## 5. ALUR LOGIKA UTAMA (FLOWCHART NARATIF)

Gunakan ini untuk menjelaskan "Cara Kerja Sistem" di laporan.

### A. Alur Assign Data ke Peta (Admin)
1.  Admin membuka menu "Grid Kotak".
2.  Admin memilih nomor Grid (misal: 1219).
3.  Sistem menampilkan detail grid.
4.  Admin memilih tombol "Assign Data".
5.  Sistem menampilkan daftar Data Survei yang tersedia.
6.  Admin mencentang satu atau lebih survei.
7.  Admin menyimpan.
8.  Sistem memperbarui tabel pivot `grid_seismik` dan mengubah status grid menjadi `filled`.

### B. Alur Download Dokumen (Pegawai)
1.  Pegawai registrasi dengan email `@esdm.go.id`.
2.  Sistem mengirim link verifikasi ke email (simulasi/log).
3.  Pegawai klik link verifikasi -> Akun Aktif.
4.  Pegawai login -> Buka Katalog.
5.  Pada detail survei, tombol "Download File Asli" muncul (yang tidak terlihat oleh publik).
6.  Pegawai klik download -> Controller memvalidasi sesi -> File disajikan (stream download).

---

## 6. STRUKTUR KODE PENTING (SOURCE CODE)

*   **`CreateGridKotakTable` (Migration)**: Definisi struktur tabel grid spasial.
*   **`DataSurveiController`**: Mengatur logika CRUD data survei.
*   **`PetaController`**: Mengatur data JSON yang dikirim ke frontend peta Leaflet.
*   **`GridKotakController`**: Logika menghubungkan (attach) dan memutus (detach) relasi data survei dengan grid.
*   **`resources/views/User/peta/index.blade.php`**: File view utama yang memuat peta & script Leaflet.js.

---

## 7. KESIMPULAN TEKNIS (UNTUK PENUTUP)
Sistem ini berhasil mengimplementasikan integrasi antara data tekstual (arsip) dengan data spasial (peta) menggunakan relasi *Many-to-Many*. Penggunaan Laravel 12 menjamin keamanan dan skalabilitas, sementara Leaflet.js memberikan antarmuka visual yang ringan tanpa biaya lisensi (Open Source).
