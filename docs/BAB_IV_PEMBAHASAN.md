# BAB IV
# PEMBAHASAN

---

## 4.1 Gambaran Umum Proyek

**Sistem Informasi Data Peta Seismik** adalah sebuah aplikasi berbasis web yang dikembangkan untuk Balai Besar Survei dan Pemetaan Geologi Laut (BBSPGL). Proyek ini bertujuan untuk mendigitalisasi pengelolaan dan visualisasi data hasil survei seismik yang sebelumnya masih dikelola secara terpisah-pisah dan kurang terintegrasi.

Latar belakang pengembangan sistem ini didasari oleh kebutuhan BBSPGL akan sebuah platform terpusat yang dapat menyimpan arsip data survei, menampilkannya dalam bentuk peta interaktif, serta memudahkan pencarian informasi bagi pihak internal maupun publik. Sebelum adanya sistem ini, pencarian data historis memerlukan waktu yang cukup lama dan visualisasi sebaran lokasi survei sulit dilakukan secara cepat.

Dengan sistem ini, data survei tidak hanya tersimpan rapi dalam database, tetapi juga dipetakan secara spasial menggunakan sistem grid pada peta digital. Hal ini memudahkan pengguna untuk melihat cakupan wilayah yang sudah disurvei dan mengidentifikasi area yang belum terpetakan.

---

## 4.2 Analisis Kebutuhan Sistem

Analisis kebutuhan sistem dilakukan untuk menentukan spesifikasi fitur dan batasan yang harus dipenuhi agar sistem dapat berjalan optimal dan menyelesaikan permasalahan yang ada.

### 4.2.1 Kebutuhan Fungsional

Kebutuhan fungsional mendefinisikan fitur-fitur spesifik yang dapat dilakukan oleh sistem. Berdasarkan hasil analisis, berikut adalah kebutuhan fungsional utama:

1.  **Manajemen Pengguna (Autentikasi & Otorisasi)**
    *   Sistem dapat memvalidasi login Admin untuk akses panel pengelolaan.
    *   Sistem menyediakan fitur registrasi untuk Pegawai Internal dengan validasi domain email instansi (`@esdm.go.id`).
    *   Sistem membedakan hak akses antara Admin, Pegawai Internal, dan Pengguna Publik.

2.  **Manajemen Data Survei**
    *   Admin dapat melakukan operasi CRUD (Create, Read, Update, Delete) terhadap data survei.
    *   Setiap data survei mencakup informasi detail seperti Judul, Tahun, Tipe Survei, Wilayah, Deskripsi, dan Gambar Pratinjau.
    *   Sistem mendukung upload gambar dan pengelolaan file terkait.

3.  **Visualisasi Peta & Grid Seismik**
    *   Sistem mampu menampilkan peta interaktif (menggunakan Leaflet.js).
    *   Admin dapat membuat dan mengelola Grid Kotak pada peta.
    *   Sistem dapat menghubungkan (assign) data survei ke dalam grid tertentu.
    *   Peta menampilkan indikator visual (warna) untuk membedakan grid yang kosong dan grid yang berisi data.

4.  **Katalog & Pencarian**
    *   Pengguna Publik dapat melihat katalog data survei.
    *   Tersedia fitur pencarian berdasarkan kata kunci dan filter berdasarkan Tahun, Tipe, atau Wilayah.
    *   Pengguna dapat melihat detail lengkap dari setiap item survei.

5.  **Pelaporan (Reporting)**
    *   Admin dapat mengekspor rekapitulasi data survei dalam format **PDF**.
    *   Admin dapat mengekspor data dalam format **Excel** untuk pengolahan lebih lanjut.

### 4.2.2 Kebutuhan Non-Fungsional

Kebutuhan non-fungsional mencakup aspek performa, keamanan, dan lingkungan operasional sistem:

1.  **Perangkat Keras (Server/Development)**
    *   Processor minimal setara Intel Core i3 / AMD Ryzen 3.
    *   RAM minimal 4GB (8GB direkomendasikan).
    *   Koneksi internet stabil untuk memuat peta dari penyedia layanan peta (OpenStreetMap).

2.  **Perangkat Lunak**
    *   Sistem Operasi: Windows/Linux/macOS.
    *   Web Server: Apache/Nginx (via Laragon/XAMPP).
    *   Bahasa Pemrograman: PHP (Framework Laravel).
    *   Database: MySQL.
    *   Browser: Google Chrome, Firefox, atau Microsoft Edge versi terbaru.

3.  **Keamanan**
    *   Password pengguna harus disimpan dalam bentuk terenkripsi (Bcrypt).
    *   Validasi input untuk mencegah serangan seperti SQL Injection dan XSS.
    *   Proteksi CSRF (Cross-Site Request Forgery) pada setiap form input.

4.  **Antarmuka Pengguna (UI/UX)**
    *   Desain antarmuka harus responsif (dapat diakses via desktop maupun mobile).
    *   Navigasi harus intuitif dan mudah dipahami oleh pengguna awam.

---

## 4.3 Perancangan dan Pengembangan Sistem

Tahap ini menjelaskan bagaimana sistem dirancang dan dikembangkan untuk memenuhi kebutuhan yang telah didefinisikan sebelumnya.

### 4.3.1 Proses Pengembangan Sistem

Proses pengembangan sistem mengikuti siklus hidup pengembangan perangkat lunak (SDLC) model **Waterfall**, yang terdiri dari tahapan berikut:

1.  **Analisis Kebutuhan**: Mengumpulkan informasi melalui wawancara dan observasi alur kerja pengelolaan data di BBSPGL saat ini.
2.  **Perancangan Sistem (Design)**:
    *   **Perancangan Database**: Membuat ERD (Entity Relationship Diagram) untuk memodelkan tabel `admin`, `users`, `data_survei`, `grid_kotak`, dan relasinya.
    *   **Perancangan Antarmuka**: Membuat sketsa/wireframe halaman dashboard admin dan katalog publik.
    *   **Perancangan Arsitektur**: Menentukan penggunaan arsitektur MVC (Model-View-Controller) pada Laravel.
3.  **Implementasi (Coding)**: Penulisan kode program menggunakan Laravel untuk backend, Blade Template untuk frontend, dan MySQL untuk database.
4.  **Pengujian (Testing)**: Melakukan pengujian fungsionalitas fitur (Black Box Testing) untuk memastikan tidak ada error.
5.  **Deployment**: Instalasi sistem pada server lokal atau hosting untuk digunakan.

### 4.3.2 Pembagian Tugas Tim

*(Catatan: Jika PKL dilakukan mandiri, bagian ini dapat disesuaikan. Jika tim, sebutkan pembagian tugasnya. Di bawah ini adalah contoh untuk pengerjaan mandiri/dominan)*

Selama pelaksanaan proyek PKL ini, pengembangan sistem dilakukan secara **mandiri** oleh penulis. Oleh karena itu, penulis bertanggung jawab atas seluruh aspek pengembangan, meliputi:

*   **System Analyst**: Menganalisis kebutuhan user dan merancang alur sistem.
*   **UI/UX Designer**: Merancang tampilan antarmuka website agar user-friendly.
*   **Backend Developer**: Membangun logika server, API, controller, dan manajemen database.
*   **Frontend Developer**: Mengimplementasikan desain ke dalam kode HTML, CSS, JavaScript, dan integrasi peta Leaflet.
*   **Tester**: Melakukan uji coba sistem untuk mencari bug dan memastikan error handling berjalan baik.

---

## 4.4 Implementasi Sistem

Subbab ini menjabarkan hasil nyata dari tahapan coding, dimana rancangan diubah menjadi aplikasi yang berfungsi.

**1. Halaman Dashboard Admin**
Halaman ini adalah pusat kontrol bagi administrator. Terdapat ringkasan statistik jumlah data survei, jumlah grid yang terisi, dan aktivitas terbaru. Dashboard dibangun menggunakan template admin yang responsif.

**2. Fitur Peta Interaktif (Grid System)**
Fitur unggulan sistem ini adalah peta interaktif.
*   **Implementasi**: Menggunakan library **Leaflet.js**.
*   **Fungsi**: Peta menampilkan grid-grid kotak di atas wilayah laut Indonesia.
*   **Logika**: Setiap grid memiliki status (warna hijau/merah/transparan) tergantung ketersediaan data. Admin dapat mengklik grid untuk melihat atau menambahkan data survei ke koordinat tersebut.

**3. Manajemen Data Survei**
Formulir input data dibuat lengkap dengan validasi. Saat admin mengunggah data, sistem secara otomatis memproses file gambar dan menyimpannya di direktori `storage` yang aman. Data disimpan ke tabel MySQL dengan relasi ke tabel admin (pengunggah).

**4. Portal Publik & Katalog**
Sisi frontend untuk publik menampilkan data dalam bentuk kartu (cards) yang rapi. Fitur filter menggunakan parameter URL (`?tahun=2023&tipe=2D`) yang ditangani oleh controller untuk memfilter query database secara dinamis.

**5. Ekspor Laporan**
Fitur pelaporan diimplementasikan menggunakan library tambahan:
*   **DomPDF**: Untuk men-generate file PDF laporan data survei yang siap cetak.
*   **PhpSpreadsheet**: Untuk membuat file Excel yang berisi raw data bagi keperluan analisis lebih lanjut oleh staff BBSPGL.

---

## 4.5 Pengujian dan Evaluasi Sistem

Pengujian dilakukan menggunakan metode **Black Box Testing**, dimana penguji fokus pada output yang dihasilkan sistem dari berbagai input tanpa melihat kode internalnya.

**Skenario Pengujian Utama:**

| No | Modul | Skenario | Hasil yang Diharapkan | Hasil Pengujian |
|----|-------|----------|-----------------------|-----------------|
| 1 | Login | Admin memasukkan email/password salah | Muncul pesan error "Kredensial tidak cocok" | **Berhasil** (Sesuai) |
| 2 | CRUD Data | Admin menambah data survei baru | Data muncul di tabel dan notifikasi sukses tampil | **Berhasil** (Sesuai) |
| 3 | Peta Grid | User mengklik grid pada peta | Popup info detail grid muncul | **Berhasil** (Sesuai) |
| 4 | Search | User mencari kata kunci "Seismik 2D" | Hanya data yang mengandung kata tersebut yang tampil | **Berhasil** (Sesuai) |
| 5 | Ekspor | Admin klik tombol "Export PDF" | File PDF terunduh dan formatnya rapi | **Berhasil** (Sesuai) |

**Evaluasi:**
Berdasarkan hasil pengujian, sistem berfungsi dengan baik dan stabil. Fitur-fitur krusial seperti manajemen data dan peta interaktif berjalan tanpa kendala error (bug) yang fatal. Antarmuka dinilai cukup responsif dan mudah dipahami.

---

## 4.6 Kendala yang Dihadapi

Selama proses pengembangan sistem ini, terdapat beberapa kendala yang dihadapi, antara lain:

1.  **Kendala Teknis - Integrasi Peta**:
    Tantangan terbesar adalah mengintegrasikan logika database dengan peta Leaflet, khususnya dalam memetakan data survei ke dalam sistem grid yang akurat secara koordinat geografis. Menangani rendering banyak grid sekaligus sempat mempengaruhi performa browser.

2.  **Kendala Data**:
    Ketersediaan data dummy yang representatif untuk pengujian peta cukup terbatas pada awal pengembangan, sehingga perlu simulasi data manual.

3.  **Penyesuaian Framework**:
    Penyesuaian versi library pihak ketiga (seperti DomPDF dan export Excel) dengan versi Laravel yang digunakan memerlukan konfigurasi khusus agar tidak terjadi konflik dependensi.

**Upaya Penanganan**:
Untuk mengatasi kendala peta, dilakukan optimasi kode JavaScript dan penggunaan format data GeoJSON yang lebih efisien. Kendala library diatasi dengan membaca dokumentasi resmi dan melakukan update driver yang sesuai.

---

## 4.7 Hasil dan Manfaat Sistem

**Hasil Akhir**:
Menghasilkan sebuah **Sistem Informasi Data Peta Seismik** berbasis web yang siap digunakan (deploy-ready). Sistem ini memiliki dua antarmuka utama: Panel Admin (Backend) untuk pengelolaan data, dan Portal Publik (Frontend) untuk penyajian informasi.

**Manfaat bagi Instansi (BBSPGL)**:
1.  **Efisiensi Kerja**: Memangkas waktu pencarian data yang sebelumnya manual menjadi hitungan detik melalui fitur *search*.
2.  **Keamanan Data**: Data tersimpan digital dalam database terpusat, mengurangi risiko kehilangan arsip fisik.
3.  **Pemetaan Visual**: Memudahkan pimpinan atau peneliti melihat persebaran lokasi survei melalui peta visual yang informatif.

**Manfaat bagi Pengguna**:
Masyarakat atau peneliti eksternal dapat dengan mudah mengakses informasi ketersediaan data survei geologi kelautan tanpa harus datang langsung ke kantor, meningkatkan transparansi layanan publik.

---

## 4.8 Pembahasan Akhir

Secara keseluruhan, pelaksanaan proyek pembuatan Sistem Informasi Data Peta Seismik ini telah berjalan lancar dan mencapai target yang ditetapkan. Sistem yang dibangun mampu menjawab rumusan masalah mengenai sulitnya pengelolaan dan visualisasi data survei.

**Kelebihan Sistem**:
*   Desain modern dan responsif.
*   Fitur peta interaktif yang informatif (tidak hanya teks).
*   Kemudahan penggunaan (User Friendly) bagi admin non-IT.

**Keterbatasan**:
*   Sistem saat ini baru mencakup data Seismik, belum terintegrasi penuh dengan jenis data geologi lainnya (seperti data batimetri atau geomagnet) dalam satu peta terpadu.
*   Fitur pencarian peta masih berbasis visual klik, belum mendukung pencarian *spatial query* (misalnya: mencari data dalam radius 5 km dari titik koordinat tertentu).

**Rekomendasi Pengembangan**:
Di masa depan, sistem ini dapat dikembangkan lebih lanjut dengan menambahkan fitur *WebGIS* yang lebih advanced, integrasi API dengan sistem kementerian pusat (ESDM), serta penambahan modul analitik data otomatis untuk kebutuhan riset.
