# BAB IV
# PEMBAHASAN

---

## 4.1 Gambaran Umum Proyek

Proyek yang dikerjakan selama pelaksanaan Praktik Kerja Lapangan (PKL) adalah pengembangan **Sistem Informasi Data Peta Seismik** untuk Balai Besar Survei dan Pemetaan Geologi Laut (BBSPGL). Sistem ini merupakan aplikasi web yang dirancang untuk mengelola dan menampilkan data hasil survei geologi kelautan secara digital dan interaktif.

Latar belakang pengembangan sistem ini adalah kebutuhan BBSPGL untuk memiliki platform terpusat dalam mengelola data survei seismik yang selama ini tersimpan dalam berbagai format dan lokasi yang berbeda. Dengan adanya sistem berbasis web, data survei dapat diakses dengan mudah baik oleh pihak internal maupun masyarakat umum yang membutuhkan informasi tersebut.

Sistem ini dibangun menggunakan framework Laravel dengan arsitektur Model-View-Controller (MVC) yang memisahkan logika bisnis, tampilan, dan pengelolaan data. Fitur utama yang dikembangkan meliputi manajemen data survei, visualisasi peta interaktif menggunakan sistem grid persegi, katalog publik untuk akses informasi, serta fitur ekspor laporan dalam format PDF dan Excel.

Pengembangan sistem dilakukan secara bertahap dimulai dari analisis kebutuhan, perancangan database dan antarmuka, implementasi fitur-fitur utama, hingga pengujian sistem. Selama proses pengembangan, dilakukan iterasi perbaikan berdasarkan masukan dan hasil evaluasi untuk memastikan sistem berjalan sesuai dengan kebutuhan pengguna.

---

## 4.2 Analisis dan Perancangan Sistem

### 4.2.1 Analisis Kebutuhan Sistem

Berdasarkan hasil analisis yang dilakukan, kebutuhan sistem dapat dibagi menjadi dua kategori utama:

#### A. Kebutuhan Fungsional

Kebutuhan fungsional adalah fungsi-fungsi yang harus dapat dilakukan oleh sistem. Berikut adalah kebutuhan fungsional sistem:

| No | Kebutuhan | Deskripsi |
|----|-----------|-----------|
| 1 | Autentikasi Admin | Sistem harus menyediakan mekanisme login dan logout untuk administrator |
| 2 | Manajemen Data Survei | Admin dapat menambah, mengubah, menghapus, dan melihat data survei |
| 3 | Manajemen Grid Kotak | Admin dapat mengelola lokasi grid pada peta dan menghubungkannya dengan data survei |
| 4 | Katalog Publik | Pengguna umum dapat melihat daftar survei yang tersedia dengan fitur pencarian dan filter |
| 5 | Peta Interaktif | Sistem menampilkan peta dengan visualisasi lokasi survei menggunakan grid kotak |
| 6 | Ekspor Laporan | Admin dapat mengekspor data survei dalam format PDF dan Excel |
| 7 | Dashboard Statistik | Sistem menampilkan ringkasan statistik data survei untuk admin |
| 8 | Registrasi Pegawai | Pegawai internal dapat mendaftar dengan verifikasi email domain @esdm.go.id |
| 9 | Download File Scan | Pegawai terverifikasi dapat mengunduh file scan asli hasil survei |
| 10 | Halaman Informasi | Sistem menyediakan halaman tentang kami, kontak, FAQ, dan panduan |

#### B. Kebutuhan Non-Fungsional

Kebutuhan non-fungsional berkaitan dengan aspek kualitas sistem:

| No | Kebutuhan | Deskripsi |
|----|-----------|-----------|
| 1 | Keamanan | Sistem menerapkan autentikasi, otorisasi, dan proteksi terhadap serangan umum (CSRF, XSS, SQL Injection) |
| 2 | Responsif | Tampilan sistem dapat menyesuaikan dengan berbagai ukuran layar perangkat |
| 3 | Performa | Halaman dapat dimuat dalam waktu kurang dari 3 detik |
| 4 | Kemudahan Penggunaan | Antarmuka intuitif dan mudah digunakan oleh pengguna dengan berbagai tingkat keahlian |
| 5 | Skalabilitas | Sistem dapat menangani penambahan data tanpa penurunan performa signifikan |

#### C. Identifikasi Pengguna (Aktor)

Sistem ini memiliki tiga jenis pengguna utama:

1. **Administrator (Admin)**
   - Bertanggung jawab mengelola seluruh data dalam sistem
   - Memiliki akses penuh ke panel admin
   - Dapat mengelola data survei, grid kotak, dan pengaturan sistem

2. **Pegawai Internal**
   - Pegawai BBSPGL dengan email domain @esdm.go.id
   - Dapat mengakses katalog dan mengunduh file scan asli
   - Memerlukan verifikasi email untuk aktivasi akun

3. **Pengguna Publik (User)**
   - Masyarakat umum yang mengakses website
   - Dapat melihat katalog survei dan peta interaktif
   - Tidak memerlukan registrasi atau login

---

### 4.2.2 Perancangan Sistem

#### A. Use Case Diagram

Use Case Diagram menggambarkan interaksi antara aktor dengan sistem. Berikut adalah daftar use case berdasarkan aktor:

**Tabel Use Case Admin:**
| No | Use Case | Deskripsi |
|----|----------|-----------|
| 1 | Login | Admin melakukan autentikasi ke sistem |
| 2 | Logout | Admin keluar dari sistem |
| 3 | Kelola Data Survei | Admin menambah, mengubah, menghapus, dan melihat data survei |
| 4 | Kelola Grid Kotak | Admin mengelola grid lokasi pada peta |
| 5 | Assign Data ke Grid | Admin menghubungkan data survei dengan lokasi grid |
| 6 | Export Laporan | Admin mengekspor data dalam format PDF/Excel |
| 7 | Lihat Dashboard | Admin melihat statistik dan ringkasan data |
| 8 | Kelola Pegawai | Admin menyetujui atau menolak registrasi pegawai |
| 9 | Pengaturan Akun | Admin mengubah profil dan password |

**Tabel Use Case Pegawai Internal:**
| No | Use Case | Deskripsi |
|----|----------|-----------|
| 1 | Registrasi | Pegawai mendaftar akun baru |
| 2 | Verifikasi Email | Pegawai mengaktifkan akun melalui link verifikasi |
| 3 | Login | Pegawai masuk ke sistem |
| 4 | Lihat Katalog | Pegawai melihat daftar data survei |
| 5 | Download File Scan | Pegawai mengunduh file scan asli survei |

**Tabel Use Case Pengguna Publik:**
| No | Use Case | Deskripsi |
|----|----------|-----------|
| 1 | Lihat Beranda | User melihat halaman utama website |
| 2 | Lihat Katalog | User menjelajahi katalog data survei |
| 3 | Cari & Filter Data | User mencari data berdasarkan kriteria |
| 4 | Lihat Peta Interaktif | User melihat peta dengan lokasi survei |
| 5 | Lihat Detail Survei | User melihat informasi lengkap survei |
| 6 | Hubungi Admin | User mengirim pesan melalui form kontak |

*(Gambar Use Case Diagram dapat dilihat pada Lampiran)*

---

#### B. Entity Relationship Diagram (ERD)

Perancangan database menggunakan Entity Relationship Diagram untuk menggambarkan hubungan antar entitas. Sistem ini memiliki lima entitas utama:

**Tabel Entitas dan Atribut:**

| Entitas | Atribut Utama | Keterangan |
|---------|---------------|------------|
| admin | id, nama, email, kata_sandi | Menyimpan data administrator |
| data_survei | id, judul, tahun, tipe, wilayah, deskripsi, gambar_pratinjau | Menyimpan data hasil survei |
| grid_kotak | id, nomor_kotak, bounds_sw_lat, bounds_sw_lng, bounds_ne_lat, bounds_ne_lng, status | Menyimpan data lokasi grid pada peta |
| grid_seismik | id, grid_kotak_id, data_survei_id, assigned_by | Tabel pivot untuk relasi many-to-many |
| pegawai_internal | id, nama, email, nip, jabatan, is_approved | Menyimpan data pegawai internal |

**Relasi Antar Entitas:**

| No | Entitas 1 | Relasi | Entitas 2 | Keterangan |
|----|-----------|--------|-----------|------------|
| 1 | admin | One to Many | data_survei | Satu admin dapat mengunggah banyak data survei |
| 2 | data_survei | Many to Many | grid_kotak | Satu survei dapat berada di banyak grid, satu grid dapat memiliki banyak survei |
| 3 | admin | One to Many | pegawai_internal | Satu admin dapat menyetujui banyak pegawai |

*(Gambar ERD dapat dilihat pada Lampiran)*

---

#### C. Flowchart Sistem

**1. Flowchart Login Admin**

```
[Mulai] → [Tampilkan Form Login] → [Input Email & Password] → 
[Validasi Input] → {Input Valid?}
    ├─ Tidak → [Tampilkan Pesan Error] → [Tampilkan Form Login]
    └─ Ya → [Cek Kredensial di Database] → {Kredensial Benar?}
                ├─ Tidak → [Tampilkan Pesan Error] → [Tampilkan Form Login]
                └─ Ya → [Buat Session] → [Redirect ke Dashboard] → [Selesai]
```

**2. Flowchart Tambah Data Survei**

```
[Mulai] → [Klik Tombol Tambah] → [Tampilkan Form Input] → 
[Input Data Survei] → [Validasi Data] → {Data Valid?}
    ├─ Tidak → [Tampilkan Pesan Error] → [Tampilkan Form Input]
    └─ Ya → [Simpan ke Database] → {Upload Gambar?}
                ├─ Ya → [Proses Upload Gambar] → [Simpan Path Gambar]
                └─ Tidak → (lanjut)
            → [Tampilkan Pesan Sukses] → [Redirect ke Daftar] → [Selesai]
```

**3. Flowchart Pencarian Katalog**

```
[Mulai] → [Tampilkan Halaman Katalog] → [Input Kata Kunci/Filter] → 
[Kirim Query ke Server] → [Proses Pencarian di Database] → 
{Data Ditemukan?}
    ├─ Tidak → [Tampilkan Pesan "Tidak Ada Data"] → [Selesai]
    └─ Ya → [Tampilkan Hasil Pencarian] → [Selesai]
```

---

#### D. Struktur Navigasi

**1. Navigasi Portal Publik:**
- Beranda
- Katalog
  - Daftar Survei
  - Detail Survei
- Peta Interaktif
- Tentang Kami
- Kontak
- Informasi
  - Panduan Pengguna
  - FAQ
  - Kebijakan Privasi

**2. Navigasi Panel Admin:**
- Dashboard
- Data Survei
  - Daftar
  - Tambah Baru
  - Edit
  - Detail
- Grid Kotak Seismik
- Laporan
  - Export PDF
  - Export Excel
- Pengaturan
  - Profil
  - Password
  - Pegawai Internal

---

## 4.3 Implementasi Proyek

### 4.3.1 Proses Pengembangan Sistem

Pengembangan Sistem Informasi Data Peta Seismik dilakukan menggunakan metodologi pengembangan iteratif dengan tahapan sebagai berikut:

**1. Tahap Persiapan**
- Instalasi environment pengembangan (Laragon, PHP 8.2, Composer, Node.js)
- Setup project Laravel dan konfigurasi database
- Pembuatan struktur folder dan file dasar

**2. Tahap Pengembangan Database**
- Perancangan skema database berdasarkan ERD
- Pembuatan file migration untuk setiap tabel
- Pembuatan model Eloquent dengan definisi relasi
- Pembuatan seeder untuk data awal/testing

**3. Tahap Pengembangan Backend**
- Pembuatan controller untuk setiap modul
- Implementasi logika bisnis dan validasi data
- Pembuatan middleware untuk autentikasi dan otorisasi
- Integrasi dengan library pihak ketiga (DomPDF, PhpSpreadsheet)

**4. Tahap Pengembangan Frontend**
- Pembuatan layout utama menggunakan Blade template
- Implementasi desain responsif dengan CSS
- Integrasi Leaflet.js untuk peta interaktif
- Implementasi JavaScript untuk interaksi dinamis

**5. Tahap Integrasi dan Pengujian**
- Integrasi seluruh modul sistem
- Pengujian fungsional setiap fitur
- Perbaikan bug dan optimasi performa
- Pengujian kompatibilitas browser

---

### 4.3.2 Pembagian Tugas dalam Tim

*(Sesuaikan dengan kondisi PKL kamu. Jika dikerjakan sendiri, bisa ditulis seperti ini:)*

Pengembangan sistem ini dilakukan secara mandiri oleh penulis dengan bimbingan dari pembimbing lapangan. Berikut adalah pembagian waktu dan fokus pengerjaan:

| Minggu | Fokus Pengerjaan | Hasil |
|--------|-----------------|-------|
| 1-2 | Analisis kebutuhan dan perancangan | Dokumen spesifikasi, ERD, Use Case |
| 3-4 | Setup project dan pengembangan database | Struktur project, migration, model |
| 5-6 | Pengembangan modul admin | CRUD data survei, dashboard, autentikasi |
| 7-8 | Pengembangan modul grid dan peta | Manajemen grid, integrasi Leaflet.js |
| 9-10 | Pengembangan portal publik | Katalog, pencarian, halaman informasi |
| 11-12 | Pengujian dan penyempurnaan | Bug fixing, optimasi, dokumentasi |

---

### 4.3.3 Implementasi Fitur Utama

#### A. Implementasi Autentikasi Admin

Sistem autentikasi admin dibangun menggunakan fitur authentication guards dari Laravel. Admin memiliki tabel dan model terpisah dari user biasa untuk keamanan yang lebih baik.

**Komponen yang diimplementasikan:**
- Form login dengan validasi input
- Proses autentikasi menggunakan email dan password
- Session management untuk menjaga status login
- Middleware untuk proteksi route admin
- Fitur logout dan reset password

**Teknologi yang digunakan:**
- Laravel Authentication Guards
- Bcrypt untuk enkripsi password
- CSRF Token untuk keamanan form

---

#### B. Implementasi Manajemen Data Survei

Modul data survei merupakan inti dari sistem yang memungkinkan admin mengelola informasi hasil survei geologi kelautan.

**Fitur yang diimplementasikan:**
- Daftar data survei dengan pagination
- Form tambah data survei dengan validasi
- Upload gambar pratinjau dengan resize otomatis
- Form edit untuk memperbarui data
- Fitur hapus dengan konfirmasi
- Detail data survei dengan informasi lengkap

**Atribut data survei:**
| Atribut | Tipe | Keterangan |
|---------|------|------------|
| Judul | Text | Judul/nama survei |
| Ketua Tim | Text | Nama ketua tim survei |
| Tahun | Year | Tahun pelaksanaan survei |
| Tipe | Select | Tipe survei (2D, 3D, HR) |
| Wilayah | Text | Wilayah/lokasi survei |
| Deskripsi | Textarea | Deskripsi lengkap survei |
| Gambar | File | Gambar pratinjau survei |
| File Scan | File | File scan asli (khusus pegawai) |

---

#### C. Implementasi Peta Interaktif dengan Sistem Grid

Peta interaktif menggunakan library Leaflet.js untuk menampilkan lokasi survei dalam bentuk grid kotak pada peta Indonesia.

**Komponen peta yang diimplementasikan:**
- Base map menggunakan tile layer OpenStreetMap
- Grid kotak dengan koordinat bounding box
- Pewarnaan grid berdasarkan status (terisi/kosong)
- Popup informasi saat grid diklik
- Zoom dan pan control
- Layer control untuk pengaturan tampilan

**Cara kerja sistem grid:**
1. Peta Indonesia dibagi menjadi grid-grid kotak dengan nomor unik
2. Setiap grid memiliki koordinat batas (southwest dan northeast)
3. Data survei dihubungkan dengan grid melalui tabel pivot
4. Grid yang memiliki data ditampilkan dengan warna berbeda
5. Klik pada grid menampilkan popup dengan daftar survei terkait

---

#### D. Implementasi Katalog Publik

Katalog publik menyediakan akses bagi masyarakat umum untuk menjelajahi data survei yang tersedia.

**Fitur katalog:**
- Tampilan grid kartu untuk setiap data survei
- Pencarian berdasarkan judul dan deskripsi
- Filter berdasarkan tahun, tipe, dan wilayah
- Pagination untuk navigasi halaman
- Halaman detail dengan informasi lengkap

**Implementasi pencarian dan filter:**
- Query parameter dikirim melalui URL
- Controller memproses filter dan membangun query database
- Hasil ditampilkan dengan informasi jumlah data yang ditemukan
- Tombol reset untuk menghapus semua filter

---

#### E. Implementasi Export Laporan

Fitur export memungkinkan admin mengunduh data dalam format PDF dan Excel untuk keperluan dokumentasi dan pelaporan.

**Export PDF:**
- Menggunakan library DomPDF
- Template laporan dengan header dan footer
- Tabel data survei dengan styling yang rapi
- Statistik ringkasan data

**Export Excel:**
- Menggunakan library PhpSpreadsheet
- Format spreadsheet dengan header kolom
- Data survei lengkap dalam format tabel
- Auto-width untuk kolom

---

## 4.4 Pengujian dan Evaluasi

Pengujian sistem dilakukan untuk memastikan seluruh fitur berfungsi sesuai dengan kebutuhan yang telah ditentukan. Metode pengujian yang digunakan adalah **Black Box Testing** yang berfokus pada fungsionalitas sistem tanpa melihat struktur kode internal.

**Tabel Hasil Pengujian:**

| No | Fitur yang Diuji | Skenario Pengujian | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|------------------|--------------------|-----------------------|--------------|--------|
| 1 | Login Admin | Input email dan password valid | Redirect ke dashboard | Redirect ke dashboard | ✓ Berhasil |
| 2 | Login Admin | Input password salah | Tampil pesan error | Tampil pesan error | ✓ Berhasil |
| 3 | Tambah Data Survei | Input data lengkap dengan gambar | Data tersimpan di database | Data tersimpan | ✓ Berhasil |
| 4 | Tambah Data Survei | Input data tanpa mengisi field wajib | Tampil validasi error | Tampil validasi error | ✓ Berhasil |
| 5 | Edit Data Survei | Ubah judul survei | Judul berubah di database | Judul berubah | ✓ Berhasil |
| 6 | Hapus Data Survei | Klik hapus dengan konfirmasi | Data terhapus dari database | Data terhapus | ✓ Berhasil |
| 7 | Pencarian Katalog | Input kata kunci pencarian | Tampil data yang sesuai | Tampil data sesuai | ✓ Berhasil |
| 8 | Filter Katalog | Pilih filter tahun | Tampil data sesuai tahun | Tampil data sesuai | ✓ Berhasil |
| 9 | Peta Interaktif | Klik grid kotak | Tampil popup informasi | Tampil popup | ✓ Berhasil |
| 10 | Export PDF | Klik tombol export PDF | File PDF terunduh | File terunduh | ✓ Berhasil |
| 11 | Export Excel | Klik tombol export Excel | File Excel terunduh | File terunduh | ✓ Berhasil |
| 12 | Registrasi Pegawai | Daftar dengan email @esdm.go.id | Akun terdaftar, email terkirim | Berhasil | ✓ Berhasil |
| 13 | Responsif | Akses dari perangkat mobile | Tampilan menyesuaikan | Tampilan responsif | ✓ Berhasil |

**Kesimpulan Pengujian:**

Berdasarkan hasil pengujian yang dilakukan, seluruh fitur utama sistem berfungsi dengan baik sesuai dengan kebutuhan yang telah ditetapkan. Sistem dapat menangani berbagai skenario penggunaan termasuk penanganan error dan validasi input.

---

## 4.5 Pembahasan Hasil

### A. Hasil yang Dicapai

Pengembangan Sistem Informasi Data Peta Seismik telah berhasil diselesaikan dengan fitur-fitur utama yang berfungsi sesuai dengan kebutuhan. Sistem ini menyediakan solusi digital untuk pengelolaan data survei geologi kelautan yang sebelumnya dikelola secara konvensional.

**Fitur yang berhasil diimplementasikan:**
1. Sistem autentikasi multi-level (admin dan pegawai internal)
2. Manajemen data survei dengan fitur CRUD lengkap
3. Visualisasi peta interaktif dengan sistem grid kotak
4. Katalog publik dengan pencarian dan filter
5. Export laporan dalam format PDF dan Excel
6. Dashboard dengan statistik data
7. Registrasi pegawai dengan verifikasi email

### B. Manfaat Sistem

**Bagi BBSPGL:**
- Pengelolaan data survei menjadi lebih terstruktur dan terpusat
- Kemudahan dalam pencarian dan akses data historis
- Visualisasi lokasi survei membantu perencanaan survei baru
- Pembuatan laporan menjadi lebih cepat dan efisien

**Bagi Masyarakat:**
- Akses informasi survei geologi kelautan menjadi lebih mudah
- Transparansi data hasil survei pemerintah
- Visualisasi peta memudahkan pemahaman sebaran lokasi survei

**Bagi Pegawai Internal:**
- Akses file scan asli untuk keperluan penelitian dan pekerjaan
- Proses registrasi yang mudah dengan verifikasi email

### C. Keterbatasan Sistem

Meskipun sistem telah berjalan dengan baik, terdapat beberapa keterbatasan yang dapat menjadi bahan pengembangan di masa mendatang:

1. **Fitur Pencarian Lanjutan**: Pencarian saat ini masih berbasis teks sederhana, belum mendukung pencarian spasial berdasarkan koordinat geografis.

2. **Notifikasi Real-time**: Belum ada sistem notifikasi real-time untuk memberitahu admin tentang aktivitas penting seperti registrasi pegawai baru.

3. **Multi-bahasa**: Sistem saat ini hanya tersedia dalam bahasa Indonesia, belum mendukung bahasa Inggris atau bahasa lainnya.

4. **Mobile Application**: Sistem hanya tersedia dalam versi web, belum memiliki aplikasi mobile native.

### D. Rekomendasi Pengembangan

Untuk pengembangan sistem di masa mendatang, berikut adalah beberapa rekomendasi:

1. Implementasi pencarian spasial menggunakan koordinat
2. Penambahan fitur notifikasi menggunakan WebSocket
3. Pengembangan API untuk integrasi dengan sistem lain
4. Pembuatan aplikasi mobile untuk akses yang lebih fleksibel
5. Penambahan fitur multi-bahasa
6. Implementasi sistem backup otomatis

---

*"Catatan: Sesuaikan isi dengan kondisi aktual PKL kamu, terutama pada bagian pembagian tugas tim, timeline, dan hasil pengujian."*
