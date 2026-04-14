# PANDUAN LENGKAP PERSIAPAN UJIKOM LSP - JUNIOR CODER

## Skema: Junior Coder | Proyek: Data Peta Seismik

---

## DAFTAR ISI

1. [Ringkasan Persiapan](#ringkasan-persiapan)
2. [Analisis 8 Unit Kompetensi](#analisis-8-unit-kompetensi)
3. [Contoh Jawaban Lisan untuk Setiap Unit](#contoh-jawaban-lisan)
4. [Kesalahan Umum yang Harus Dihindari](#kesalahan-umum)
5. [Strategi Terlihat Profesional](#strategi-profesional)
6. [Checklist Persiapan](#checklist-persiapan)
7. [Skenario Interview Simulasi](#skenario-interview-simulasi)

---

# BAGIAN 1: RINGKASAN PERSIAPAN

## A. Kondisi Saat Ini (Yang Sudah Anda Punya)

✅ **Database**: 5 tabel yang sudah bagus:

- `admin` → Simpan data admin yang login
- `data_survei` → Simpan data survei (judul, tahun, tipe, file gambar, dll)
- `grid_kotak` → Simpan kotak-kotak di peta
- `grid_seismik` → Tabel penghubung (grid dengan survei)
- `pegawai_internal` → Pegawai yang bisa download file

✅ **Bahasa & Tools**: Laravel, PHP, JavaScript - sudah bagus!
✅ **Fitur**: Upload file, export PDF/Excel, peta interaktif, login 2 tipe user

## B. Yang Perlu Anda Siapkan di Ujikom

Fokus pada **5 hal ini** aja:

1. **Cerita alur sistemnya** - "Pertama user lihat peta... terus klik grid... terus..."
2. **Tunjuk kode yang relevan** - Bukan teori doang, tapi buka file & tunjuk baris
3. **Jelaskan kenapa pilihan itu** - "Saya pakai grid karena... bukan marker karena..."
4. **Tahu cara cari bug** - Kalau error, tahu langkah apa yg harus dilakukan
5. **Basic security** - Validasi input, cegah hacker, enkripsi password

---

# BAGIAN 2: ANALISIS 8 UNIT KOMPETENSI

## UNIT 1: MENGGUNAKAN STRUKTUR DATA

### Apa Itu Struktur Data?

Struktur data = cara Anda organize data. Bayangkan seperti almari:

- Ada laci untuk pakaian (array)
- Ada tas untuk barang-barang kecil (collection)
- Ada hubungan antar laci (relasi database)

Di proyek Anda ada 3 jenis:

**1. Array (Koleksi data sederhana)**
Contohnya: Hitung berapa survey tipe 2D, 3D, HR

```php
$statistik = [
    '2D' => 10,
    '3D' => 5,
    'HR' => 3
]
```

**2. Relasi di Database (Hubungan antar tabel)**
Bayangkan:

- 1 admin bisa upload banyak survey (relasi 1 ke banyak)
- 1 survey bisa di banyak grid (relasi banyak ke banyak)
- 1 grid bisa punya banyak survey (relasi banyak ke banyak)

**3. Transform Data (Ubah bentuk data)**
Guna ngubah data dari database jadi format yang cocok untuk peta/Excel

### Kemungkinan Pertanyaan Asesor

**Q1**: "Jelaskan relasi Many-to-Many di sistem Anda!"

**Jawaban Simple**:

> "Satu survey itu panjang - bisa lewat banyak kotak grid. Satu kotak grid juga bisa punya banyak survey dari tahun berbeda. Jadi banyak ke banyak. Di database saya ada tabel penghubung namanya `grid_seismik` yang nyimpen hubungan ini. Jadi grid_seismik itu kayak catatan: 'Survey nomor 5 ada di grid 1019 sama grid 1020'."

**Q2**: "Pakai array untuk apa?"

**Jawaban**:

> "Di controller peta saya, ada array $stats yang nyimpen statistik: berapa total grid, berapa grid yang terisi, berapa yang kosong. Trus ada juga array tipe_data yang hitung: berapa survey 2D, 3D, HR. Keuntungannya: cepat, mudah di-loop buat tampilin di halaman."

**Q3**: "Kapan pakai array, kapan pakai data dari database?"

**Jawaban**:

> "Kalau data sedikit dan statis, pakai array. Tapi kalau data banyak dan harus dari database (kayak semua survey), langsung ambil dari database. Yang penting: query database itu harus efisien, jangan ambil semua terus filter di kode (ini lambat!)."

### Cara Jawab Saat Asesor Tanya

**Asesor**: "Kenapa pakai relasi Many-to-Many? Bukan cara lain?"

**Jawaban** (Cukup 30-40 detik, gampang):

> "Karena survey itu panjang, bisa spanning banyak grid area. Terus grid juga bisa punya survey banyak dari tahun berbeda. Jadi ga bisa 1 survey 1 grid. Mesti banyak ke banyak. Caranya: pakai tabel penghubung namanya grid_seismik yang catat: 'Survey X di grid Y, Z, W'. Simpel!"

**Apa yang perlu diingat Asesor lihat:**
✅ Anda tahu kenapa pilih ini
✅ Anda tahu caranya bikin relasi di database
✅ Anda bisa tunjuk di file mana implementasinya

---

## UNIT 2: MENGGUNAKAN SPESIFIKASI PROGRAM

### Apa Itu Spesifikasi?

Spesifikasi = "Pengguna butuh apa? Terus saya pakai teknologi apa buat kerjain itu?"

**Spesifikasi Sistem Anda (Simpel):**

| Butuh Apa    | Pakai Apa                                 | Kenapa                                               |
| ------------ | ----------------------------------------- | ---------------------------------------------------- |
| **Web app**  | Laravel 12                                | Ada semua fitur yg saya butuh (login, database, dll) |
| **Database** | MySQL                                     | Bisa nyimpen data dengan relasi kompleks             |
| **Login**    | Dual guard (admin + pegawai)              | Admin & pegawai punya hak akses beda                 |
| **Fitur**    | Peta, katalog, export PDF/Excel           | User bisa lihat peta, cari survei, export laporan    |
| **Keamanan** | Validasi input, hash password, middleware | Cegah hacker yang mau nyerang sistem                 |

### Kemungkinan Pertanyaan Asesor

**Q1**: "Jelaskan spesifikasi proyek Anda!"
**Q2**: "Mengapa pilih Laravel?"
**Q3**: "Keamanan apa yang Anda terapkan?"

### Cara Jawab (Gampang!)

**Asesor**: "Ceritakan requirement dan spesifikasi sistem Anda!"

**Jawaban** (Cukup 60 detik):

> "Proyek ini untuk digitalisasi data survei seismik. Requirement-nya:
>
> - Admin butuh manage data survei (CRUD)
> - Pegawai butuh download file survey
> - Publik butuh lihat peta dan cari survey
>
> Terus saya pilih teknologi:
>
> - Laravel buat backend (soalnya ada semua fitur yg saya butuh: login, database, security)
> - MySQL buat database (soalnya bisa relasi kompleks)
> - Leaflet.js buat peta interaktif
>
> Keamanan: password di-hash, input harus valid, cegah SQL injection, file hanya bisa didownload pegawai yg approved aja."

**Yang perlu asesor lihat:** ✅ Anda tahu kenapa pilih teknologi itu

---

## UNIT 3: PERCABANGAN, PERULANGAN, & MULTIMEDIA

### Percabangan (If-Else) - Logika Memilih

**Analogi**: Percabangan itu kayak "Jika... maka..."

- Kalau user search "peta" → tampilkan survey yg ada kata "peta"
- Kalau user pilih tahun 2023 → tampilkan hanya tahun 2023
- Kalau user ga input apa-apa → tampilkan semuanya

**Di Kode Anda Ada Di**: KatalogController.php (line buat search & filter)

**Contoh Simple**:

```php
if (user_search_gajadi_kosong) {  // Jika user ada search
    cari di database dengan keyword itu
}
if (user_pilih_tahun) {  // Jika user pilih tahun
    filter tahun itu aja
}
```

### Perulangan (Loop) - Ngulang Berkali-Kali

**Analogi**: Perulangan itu kayak "Untuk setiap..."

- Untuk setiap grid di peta, tampilkan data survei yg ada
- Untuk setiap survey, tulis ke baris Excel
- Untuk setiap gambar upload, resize jadi thumbnail

**Di Kode Anda Ada Di**: PetaController.php (tampilkan grid peta)

**Contoh Simple**:

```php
untuk setiap grid {
  ambil semua survey dalam grid itu
  untuk setiap survey dalam grid itu {
    ubah formatnya, trus tampilin di peta
  }
}
```

### Multimedia - Handle File (Gambar, PDF, Excel)

**Apa Aja Fitur Multimedia Anda:**

**1. Upload Gambar**

- Admin upload foto survey → sistem simpan ke folder → resize jadi 3 ukuran (cover, thumbnail, medium)

**2. Export PDF**

- User klik "Export PDF" → sistem buat file PDF → automatic download

**3. Export Excel**

- User klik "Export Excel" → sistem buat file Excel dengan tabel data → automatic download

### Kemungkinan Pertanyaan Asesor

**Q1**: "Tunjukkan di mana ada percabangan (if-else)!"
**Q2**: "Di mana pakai perulangan (loop)?"
**Q3**: "Multimedia apa yg Anda bikin?"
**Q4**: "Kalau upload file error, bagaimana?"

### Cara Jawab (Sangat Mudah!)

**Asesor**: "Tunjukkan if-else, loop, dan multimedia di kode Anda!"

**Jawaban** (Cukup 90 detik, santai):

> "**Percabangan**: Di KatalogController, saya ada if untuk search. Kalau user search: cari di database. Kalau ga search: tampilkan semua. Terus ada if lagi buat filter tahun, tipe, wilayah. Jadi user bisa combine filter.
>
> **Perulangan**: Di PetaController buat tampilin peta. Ada loop 1: untuk setiap grid, ambil surveinya. Ada loop dalam loop: untuk setiap survey dalam grid, ubah formatnya jadi JSON (biar cocok buat Leaflet peta). Jadi kalau ga ada loop, cuma 1 grid yg tampil doang.
>
> **Multimedia**: Ada upload gambar (admin upload, saya resize jadi 3 ukuran), export PDF (buat laporan), export Excel (buat data analyst). Contoh: user klik export Excel → sistemnyabuat file → download automatic."

**Yang asesor lihat:**
✅ Anda tahu kapan pakai if, kapan pakai loop
✅ Anda paham file handling (upload, export)

---

## UNIT 4: BEST PRACTICES & GUIDELINES

### Apa Itu Best Practices?

Best practices = "Cara yang udah terbukti bagus dan dipakai orang banyak"

**3 Best Practices Penting:**

**1. Naming Convention (Penamaan yang jelas)**

- Variabel: `$dataSurvei` (camelCase - huruf pertama kecil, huruf berikutnya capital)
- Nama function: `editDataSurvei()` (camelCase)
- Nama class: `DataSurveiController` (PascalCase - semua huruf awal capital)
- **Hindari**: `$data`, `$x`, `$survey1` (nama yang ambigu/tidak jelas)

**2. Comment & Dokumentasi**

- Di atas setiap function, kasih keterangan: "Function ini gunanya apa?"
- Kalau ada logika tricky, kasih inline comment
- Jangan comment yang obvious (contoh: `$x = 5; // set x to 5` - tidak perlu!)

**3. Keamanan Input**

- **Validasi**: Cek apakah input dari user sesuai format (email harus valid@email.com, bukan sembarangan)
- **Sanitasi**: Bersihkan HTML yang user input (cegah "script injection")
- **Enkripsi**: Password harus di-hash, jangan disimpan plain text

### Kemungkinan Pertanyaan Asesor

**Q1**: "Bagaimana Anda buat kode yang clean dan mudah dibaca?"
**Q2**: "Apa itu DRY principle? Gimana implementasinya?"
**Q3**: "Keamanan apa yg Anda terapkan?"

### Cara Jawab

**Asesor**: "Jelaskan best practices di kode Anda!"

**Jawaban** (60 detik):

> "**Naming**: Saya pakai nama yang jelas - $dataSurvei bukan $data, editDataSurvei() bukan edit(). Ini bikin kode mudah dibaca.
>
> **Comment**: Di atas function, saya kasih PHPDoc (keterangan apa function itu), terus kalau ada logika susah saya kasih inline comment.
>
> **DRY (Don't Repeat Yourself)**: Saya hindari copy-paste. Kalau logic itu dipakai 2+ file, saya buat Service class aja. Contoh: sanitasi HTML dipakai di banyak tempat, saya buat HtmlSanitizerService sekali, terus dipakai di mana-mana.
>
> **Keamanan**: Setiap input dari user harus validasi dulu (pakai Laravel validation). Kalau ada HTML/text, saya sanitasi buat cegah hacker inject script. Password di-hash, bukan simpan plain text. Database query pakai query builder (auto prevent SQL injection)."

**Yang asesor lihat:** ✅ Anda tahu prinsip clean code dan security

---

## UNIT 5: MENGIMPLEMENTASIKAN PEMROGRAMAN TERSTRUKTUR

### Penjelasan Teknis

**Pemrograman Terstruktur = Kode terorganisir dengan alur jelas, modular, tidak spaghetti code**

**A. STRUKTUR FOLDER LARAVEL (MVC Pattern)**

```
app/
├── Models/
│   ├── Admin.php
│   ├── DataSurvei.php
│   ├── GridKotak.php
│   └── PegawaiInternal.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DataSurveiController.php
│   │   │   ├── ExportController.php
│   │   │   └── GridKotakController.php
│   │   └── User/
│   │       ├── KatalogController.php
│   │       ├── PetaController.php
│   │       └── KontakController.php
│   ├── Requests/
│   │   └── DataSurveiRequest.php
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── PegawaiMiddleware.php
├── Services/
│   ├── HtmlSanitizerService.php
│   └── ImageOptimizationService.php
└── Jobs/
    └── ProcessSurveiImage.php

routes/
├── web.php          # Routing

resources/
└── views/
    ├── admin/       # Admin views
    ├── user/        # User views
    └── layouts/     # Shared layouts

database/
├── migrations/      # Database schema
└── seeders/         # Sample data
```

**B. ALUR DATA TERSTRUKTUR (Request → Controller → Model → View)**

```
User Request
    ↓
Routing (web.php) → Tentukan Controller & Method
    ↓
Controller (Orchestrator)
    ├─ Terima Request
    ├─ Call Model Method
    ├─ Process data
    └─ Return View
    ↓
Model (Logic)
    ├─ Database query
    ├─ Relasi data
    └─ Return data
    ↓
View (Blade Template)
    ├─ Loop & display
    └─ Return HTML ke browser
    ↓
Browser Display
```

**Contoh Konkret di Sistem Anda:**

1. **User ask filter data di katalog** (Klik tombol cari)
2. **Request dikirim ke route** `GET /katalog` with params `?search=...&tahun=...`
3. **Router find KatalogController@index**
4. **Controller baca request, call model query**:

```php
$query = DataSurvei::with(['pengunggah', 'gridKotak']);
if ($request->filled('search')) {
    // Add where condition
}
$surveys = $query->paginate(12);
```

5. **Model jalankan query ke DB, return results**
6. **Controller pass data ke view**:

```php
return view('User.katalog.index', compact('surveys', 'tahun_options'));
```

7. **View loop dan tampilkan data**:

```blade
@foreach($surveys as $survei)
    <div class="card">
        {{ $survei->judul }} - {{ $survei->tahun }}
    </div>
@endforeach
```

8. **Browser tampilkan HTML**

**C. MODULARITAS (Setiap class punya satu tanggung jawab)**

✅ **Baik (Single Responsibility)**:

- `DataSurveiController` → Handle request CRUD survei
- `DataSurvei` Model → Query & relasi data survei
- `ExportController` → Handle export PDF/Excel
- `HtmlSanitizerService` → Handle sanitasi HTML
- `ProcessSurveiImage` Job → Handle image processing async

Misalnya, upload image tidak langsung diproses di controller. Dipindah ke Background Job, agar controller tidak hang. Logiknya terpisah, clean!

**D. CODE FLOW JELAS**

Setiap action jelas urutan:

**Admin Upload Survei:**

```
1. User fill form & submit
2. POST /admin/survei → DataSurveiController@store
3. DataSurveiRequest validate input
4. Controller get validated data
5. If gambar_pratinjau ada → store file
6. Create DataSurvei record
7. Queue ProcessSurveiImage job untuk thumbnail
8. Redirect with success message
```

**User Lihat Peta:**

```
1. User buka /peta
2. GET /peta → PetaController@index
3. Query GridKotak dengan relasi DataSurvei
4. Map/transform data ke format Leaflet JSON
5. Pass ke view
6. View render peta dengan Leaflet.js
7. User klik grid → AJAX call
8. Controller return data survei dalam grid
9. JS popup modal show data detail
```

**E. REUSABILITY**

Service class bisa dipakai di banyak controller:

```php
// Di KontakController
HtmlSanitizerService::sanitize($input);

// Di DataSurveiController
HtmlSanitizerService::sanitize($input);

// Di PegawaiController
HtmlSanitizerService::sanitize($input);

// Sama-sama pakai logic yang sama!
```

### Kemungkinan Pertanyaan Asesor

**Q1**: "Jelaskan alur data dari user submit form sampai tampil di browser! Tunjuk di folder structure di mana file-filenya."

**Q2**: "Mengapa Anda pisah logic di controller, model, view, dan service? Apa keuntungannya?"

**Q3**: "Tunjukkan contoh modularitas di kode Anda. Di mana Anda reuse logic?"

### Contoh Jawaban Lisan untuk Unit 5

**Asesor**: "Jelaskan bagaimana Anda structure kode Anda! Tunjukkan alur data dari request sampai response."

**Jawaban Baik** (90-120 detik):

> "Saya ikuti **MVC pattern** yang Laravel rekomendasikan. Folder dipisah jelas: Models di folder Models, Controllers di Http/Controllers, Views di resources/views, dll.
>
> Alurnya begini: User buka URL, misalnya `/katalog?search=peta`. **Router** di web.php route ke `KatalogController@index`. **Controller** terima Request object, baca parameter search, terus call `DataSurvei::with(['pengunggah'])` query. **Model** jalankan query ke database, return hasil. **Controller** dapat data, pass ke view dengan `view('katalog.index', compact('surveys'))`. **View** loop melalui $surveys dan render HTML.
>
> Kenapa separasi ini? Karena:
>
> 1. **Maintainability**: Jika logic database berubah, cukup edit Model. Jika tampilan berubah, edit View. Tidak perlu ubah kedua-duanya.
> 2. **Testability**: Mudah untuk di-test isolated. Test Model dengan mock DB. Test Controller dengan mock Model, dll.
> 3. **Reusability**: Logic sanitasi HTML di Service, bisa dipakai controller manapun. Upload image di Job class, bisa di-queue dari mana saja.
>
> Struktur folder juga clear: `app/Models` untuk model, `app/Http/Controllers/Admin` untuk admin controllers, `app/Services` untuk business logic, `app/Jobs` untuk background jobs.
>
> Dengan struktur ini, kode tidak jadi spaghetti. Alur logic jelas, modular, mudah di-maintain."

**Yang Dilihat Asesor**:

- ✅ Paham MVC architecture
- ✅ Tahu alasan separasi concern
- ✅ Code organized & scalable

---

## UNIT 6: MEMBUAT DOKUMEN KODE PROGRAM

### Apa Itu Dokumentasi Kode?

= "Tulisan yang dijelaskan 'Kode ini gunanya apa, cara pakai gimana'"

Ibaratnya: Resep masakan harus ada materials dan langkah-langkah. Kalau tidak ada dokumentasi, developer lain tidak paham dan akan ngulang pekerjaan.

### 4 Jenis Dokumentasi

**1. PHPDoc (Penjelasan Function)**

Di atas setiap function, kasih keterangan:

```php
/**
 * Mencari survei berdasarkan keyword
 *
 * Parameter: $keyword = kata yang dicari
 * Return: array dari survei yang ketemu
 */
public function search($keyword) { ... }
```

**2. Inline Comments (Penjelasan Baris Penting)**

Jika ada logika yang tidak obvious, kasih comment:

```php
// Cari kata di judul, wilayah, atau deskripsi
$query->where('judul', 'LIKE', '%' . $search . '%')
      ->orWhere('wilayah', 'LIKE', '%' . $search . '%');
```

**3. README (Penjelasan Project)**

File `README.md` di root project:

- Apa aplikasi ini?
- Tech stack apa (Laravel, MySQL, dll)?
- Cara install
- Fitur apa saja

**4. API Documentation (Penjelasan Endpoint)**

Jika ada API, jelaskan endpoint:

```
GET /api/survei/{id}
- Purpose: Ambil detail satu survei
- Parameter: id = ID survei
- Response: JSON object survei
- Status: 200 (success), 404 (not found)
```

### Kemungkinan Pertanyaan Asesor

**Q1**: "Tunjukkan dokumentasi kode Anda!"
**Q2**: "Kalau developer baru join tim Anda, dia baca apa buat understand kode?"
**Q3**: "Apa bedanya Documentation & Comment?"

### Cara Jawab (60 detik)

> "Dokumentasi saya ada di **tiga level**:
>
> **Level 1 - PHPDoc**: Di atas setiap function, saya kasih `/** ... */` yang jelaskan function itu apa, parameter apa, return value apa. Ini penting agar IDE bisa auto-complete dan developer lain tahu gimana pakai function itu.
>
> **Level 2 - Inline Comments**: Di code yang kompleks atau tidak obvious, saya kasih comment. Misalnya kenapa pakai `LIKE` daripada `=`, atau kenapa ada delay di queue job.
>
> **Level 3 - README & Docs**: File `README.md` di root project jelasin overview: tech stack, cara setup, fitur. Ada juga folder `/docs` dengan dokumentasi teknis lengkap.
>
> Tujuan dokumentasi: Kalau ada bug atau maintenance needed, developer bisa understand cepat tanpa perlu tanya. Tim bisa scale dengan mudah."

**Yang Asesor Lihat**: ✅ Kode professional, mudah di-maintain

---

## UNIT 7: MELAKUKAN DEBUGGING

### Apa Itu Debugging?

= "Proses mencari dan memperbaiki error (bug) di kode"

Ibaratnya: Dokter mencari penyakit pasien. Dengarkan gejala (error message) → Scan/test (use tools) → Diagnosis (cari penyebab) → Treatment (fix)

### Teknik Debugging Simple

**1. Baca Error Message dengan Teliti**

Error message kadang sudah memberikan clue:

```
❌ "Call to undefined method DataSurvei::gridKotak()"
Artinya: Method gridKotak() tidak ada di class DataSurvei
✅ Fix: Definisikan method atau cek typo

❌ "Trying to get property of non-object"
Artinya: Variable adalah null/kosong
✅ Fix: Cek dengan if ($var !== null) sebelum pakai

❌ "SQLSTATE error"
Artinya: Query SQL salah
✅ Fix: Lihat query di browser DevTools → check syntax
```

**2. Print Debug (Lihat Isi Variable)**

```php
// Lihat isi $dataSurvei terus stop
dd($dataSurvei);

// Lihat terus lanjut
dump($dataSurvei);

// Lihat di Laravel Log file
Log::info('Total grids:', ['count' => $grids->count()]);
```

**3. Browser DevTools (F12)**

- **Network Tab**: Lihat request ke server, apakah response error?
- **Console Tab**: Ada JS error?
- **Elements Tab**: HTML structure benar?

**4. Rails/Laravel Log File**

```
File: storage/logs/laravel-xxx.log
Buka dan lihat error message lengkap
```

### Kemungkinan Pertanyaan Asesor

**Q1**: "Anda ketemu error. Gimana cara Anda debug?"
**Q2**: "Simulasi error 'Call to undefined method'. Cara cari penyebabnya gimana?"
**Q3**: "Tools apa yang Anda pakai untuk debug?"

### Cara Jawab (60 detik)

> "Saat ketemu error, saya **stay calm dan systematic**:
>
> **Step 1 - Baca error message**: Apa yang error? Di file mana, baris berapa? Error message kadang sudah kasih clue.
>
> **Step 2 - Lihat error context**: Buka log file di `storage/logs/` untuk lihat full error trace. Ini important untuk tahu di mana exactly error terjadi.
>
> **Step 3 - Repro error**: Coba buat scenario yang trigger error, jadi bisa lihat step-by-step.
>
> **Step 4 - Isolate problem**: Pakai print debug (`dd()`, `dump()`, `Log::info()`) untuk lihat isi variable. Apakah null? Array kosong? Atau nilai yang unexpected?
>
> **Step 5 - Check tools**: Buka browser DevTools (F12) untuk lihat network request (apakah 200 atau error status?), console untuk JS error.
>
> **Step 6 - Fix & verify**: Setelah tahu root cause, fix di code. Clear cache dengan `php artisan cache:clear`. Refresh browser dengan hard refresh (Ctrl+Shift+Del). Test lagi.
>
> Important: **Jangan random coba-coba, harus systematic!**"

**Yang Asesor Lihat**: ✅ Tahu tools dan approach yang proper

---

## UNIT 8: MELAKSANAKAN PENGUJIAN UNIT PROGRAM

### Apa Itu Testing?

= "Proses check apakah fitur/function bekerja sesuai spec atau tidak"

Ibaratnya: Produksi mobil. QA harus test semua component sebelum jual:

- Starter bekerja?
- Rem berfungsi?
- AC dingin?
- Semua lampu nyala?

### 2 Jenis Testing Penting

**1. BLACK BOX TESTING (Test dari user perspective)**

= "User/QA test tanpa lihat kode internal"

Contoh test case di aplikasi Anda:

```
Test 1: Search Survei
Step: Masuk katalog → isi search "peta" → enter
Harapan: Halaman tampilkan survei dengan kata "peta"
Hasil: ✅ PASS (survei dengan judul "Survei Peta Jawa Timur" muncul)

Test 2: Filter Tahun
Step: Pilih tahun 2023 di dropdown → klik Apply
Harapan: Hanya survei tahun 2023 yang tampil
Hasil: ✅ PASS

Test 3: Upload File
Step: Admin klik upload → pilih file → simpan
Harapan: File ter-save di database dan folder storage
Hasil: ❌ FAIL (error message 'File terlalu besar')
Keterangan: Upload limit 5MB, file yang upload 7MB

Test 4: Login
Step: Masukkan email & password yang salah → login
Harapan: Error message tampil "Email atau password salah"
Hasil: ❌ FAIL (tidak ada error, page hang)
Keterangan: Bug di login process, perlu fix
```

**2. WHITE BOX TESTING (Test dari developer perspective)**

= "Developer test dengan lihat kode & logic internal"

Contoh: Fungsi `filterSurvei()` punya 2 branch (if statement):

```php
public function filterSurvei($search, $tahun) {
    if (!empty($search)) {        // Branch 1: Search ada
        // ...
    }

    if (!empty($tahun)) {         // Branch 2: Tahun ada
        // ...
    }
}
```

Test case harus cover semua kemungkinan:

```
Test 1: Search + Tahun (both true)
Input: search="peta", tahun="2023"
Expected: Filter kedua-duanya

Test 2: Search Only (1st true, 2nd false)
Input: search="peta", tahun=""
Expected: Filter hanya search

Test 3: Tahun Only (1st false, 2nd true)
Input: search="", tahun="2023"
Expected: Filter hanya tahun

Test 4: No Filter (both false)
Input: search="", tahun=""
Expected: Show semua survei

Result: 4/4 branch covered = 100% coverage ✅
```

### Kemungkinan Pertanyaan Asesor

**Q1**: "Jelaskan jenis testing yang Anda lakukan (Black Box vs White Box)!"
**Q2**: "Buat test case untuk fitur search survei (input, expected output, hasil)!"
**Q3**: "Bagaimana Anda pastikan setiap fungsi bekerja dengan benar?"

### Cara Jawab (60-90 detik)

> "Testing saya dibagi 2 jenis:
>
> **Black Box Testing** (dari user perspective):
> Saya buat test case seperti user normal. Misalnya: 'User buka katalog, search "peta", harapnya tampil survei dengan kata peta'. Test ini tidak perlu lihat kode, cukup tahu expected behavior.
>
> Setiap test case berisi:
>
> - Input: Apa yang user lakukan
> - Expected Output: Apa yang seharusnya terjadi
> - Actual Output: Apa yang actually terjadi
> - Status: PASS atau FAIL
>
> **White Box Testing** (dari developer perspective):
> Saya lihat code logic, cari semua kemungkinan path (branch). Setiap if-statement adalah 1 path. Saya buat test untuk cover semua path, jadi tidak ada code yang tidak ter-test.
>
> Contoh: Fungsi filter() punya 2 if (search branch + tahun branch), jadi 4 kombinasi: [search+tahun], [search only], [tahun only], [no filter]. Saya test semua 4 kombinasi.
>
> Tools: Saya pakai Laravel PHPUnit untuk automated testing. Run dengan `php artisan test` dan instant tahu mana yang PASS atau FAIL.
>
> Keuntungan testing:
>
> - Confidence dalam kode (tahu tidak ada bug)
> - Debugging lebih cepat (test langsung show di mana error)
> - Maintenance lebih aman (perubahan kode tidak break existing feature)"

**Yang Asesor Lihat**: ✅ Tahu perbedaan testing type, systematic QA approach
php artisan test tests/Feature/... # Run specific test file
php artisan test --filter test_search # Run test dengan nama matching

```

**C. TEST COVERAGE**

```

Goal: Test cover semua code path

- Unit Test (test individual function)
- Feature Test (test endpoint/user action)
- Goal coverage: ≥ 80%

Check coverage:
php artisan test --coverage

````

### Kemungkinan Pertanyaan Asesor

**Q1**: "Apa bedanya black box, white box, grey box testing? Tuliskan contoh untuk sistem Anda!"

**Q2**: "Tunjukkan test case Anda! Bagaimana cara test menggunakan PHPUnit?"

**Q3**: "Bagaimana Anda memastikan semua functionality sudah tested?"

### Contoh Jawaban Lisan untuk Unit 8

**Asesor**: "Jelaskan pengujian yang Anda lakukan di sistem! Tunjukkan test case black box dan white box."

**Jawaban Baik** (90-120 detik):

> "Saya implementasikan **three level testing**:
>
> **Black Box Testing**: User perspective, tanpa lihat code. Test functionality:
>
> - Search: Masuk katalog, search 'peta', pastikan yang tampil hanya survei yang sesuai
> - Filter: Pilih tahun 2023, pastikan hanya tahun 2023 yang tampil
> - Export: Klik export Excel, pastikan file ter-download dengan format benar
> - Pagination: Buka halaman 2, pastikan data berbeda dari halaman 1
> - Upload: Admin upload survei baru, pastikan muncul di katalog
>
> Test ini important dari user perspective - meski internal code bagus, kalau user experience buruk, sistem tetap fail.
>
> **White Box Testing**: Developer perspective, knowing code structure. Test internal logic:
>
> - Parameter validation: Upload file > 5MB, harusnya error 'File terlalu besar'
> - SQL query: Filter tahun=2023 harusnya `WHERE tahun = 2023`, bukan yang lain
> - Relasi logic: Assign survei ke grid, harusnya insert di tabel pivot grid_seismik
> - Edge cases: Data kosong, submit form 2x, concurrent request - semua handle dengan baik?
>
> Saya tulis test menggunakan **PHPUnit & Laravel Test**: buat test class, prepare data, call action, assert hasil. Run dengan `php artisan test`.
>
> **Tool coverage**: Saya target minimal 80% code coverage artinya 80% dari semua code path sudah tested. Kalau ada branch yang tidak tested, ada logic yang tidak verified.
>
> Result: Dengan extensive testing, saya confident bahwa sistem berjalan sesuai spec, tidak ada surprise bug di production."

**Yang Dilihat Asesor**:

- ✅ Tahu 3 jenis testing
- ✅ Paham test framework & tools
- ✅ Systematic testing approach

---

# BAGIAN 3: KEMUNGKINAN PERTANYAAN MENDALAM ASESOR

## KATEGORI A: PERTANYAAN TEKNIS MENDALAM

### A1. Database & ORM

**Q**: "Jelaskan mengapa Anda memilih relasi Many-to-Many untuk grid vs alternatif lain!"
**Expected Understanding**:

- Tahu the 4 jenis relasi (One-to-One, One-to-Many, Many-to-Many, Polymorphic)
- Bisa explain trade-off setiap option
- Tahu kekurangan & kelebihan pilihan

**Q**: "Di mana di kode Anda gunakan Eager Loading (with)? Kenapa penting?"
**Expected Understanding**:

- Paham N+1 query problem
- Tahu perbedaan lazy vs eager loading
- Bisa kalkulasi performance impact

**Q**: "Sebutkan attribute casting yang Anda pakai di Model! Jelaskan fungsinya."
**Expected Understanding**:

- Tahu attribute casting untuk data type consistency
- Tahu built-in casts (json, array, decimal, integer, boolean, datetime, dll)

### A2. Authentication & Security

**Q**: "Bagaimana sistem dual-guard (admin vs pegawai) bekerja? Jelaskan implementasinya!"
**Expected Understanding**:

- Tahu about guards di config/auth.php
- Bisa jelaskan middleware untuk filter route
- Tahu session management per guard

**Q**: "Di mana di kode Anda check role/permission?"
**Expected Understanding**:

- Familiar dengan concept roles & permissions
- Tahu middleware untuk authorization
- Bisa implement simple role check

**Q**: "Bagaimana Anda secure file download untuk pegawai?"
**Expected Understanding**:

- Tahu perbedaan public vs private disk
- Understand stream response, jangan serve file dari public folder
- Bisa implement access control di controller

### A3. File & Storage

**Q**: "Jelaskan perbedaan gambar_pratinjau, gambar_thumbnail, gambar_medium di database! Kenapa perlu 3 versi?"
**Expected Understanding**:

- Tahu reason resizing images (performance, bandwidth, user experience)
- Paham image optimization tools (intervention/image)
- Tahu ketika use thumbnail vs medium

**Q**: "Di mana file disimpan? Kenapa pakai disk 'public'? Apa alternatifnya?"
**Expected Understanding**:

- Tahu filesystem configuration di config/filesystems.php
- Paham perbedaan 'public' disk (accessible via URL) vs 'private' (not accessible)
- Bisa explain trade-off

### A4. Query & Performance

**Q**: "Query di KatalogController ada LIKE wildcard ('%'). Apa implication untuk performance?"
**Expected Understanding**:

- Tahu LIKE dengan wildcard di depan tidak bisa use index
- Suggest optimization: full-text search, indexed columns, atau external search engine
- Aware performance considerations untuk big data

**Q**: "Bagaimana Anda handle large export (50,000 rows ke Excel)?"
**Expected Understanding**:

- Tahu memory limitation pada PHP
- Suggest: chunking, streaming, background job
- Paham trade-off antara real-time export vs async

### A5. JavaScript & Frontend

**Q**: "Di peta, saat user click grid, terjadi AJAX call. Bagaimana error handling di JavaScript?"
**Expected Understanding**:

- Tahu async/await atau .then() pattern
- Implementasi try-catch untuk error handling
- User feedback: loading state, error message, spinner

---

## KATEGORI B: PERTANYAAN BEHAVIORAL (Soft Skills)

### B1. Problem-Solving

**Q**: "Bayangkan grid peta tidak muncul di production. Bagaimana Anda handle?"
**Expected Approach**:

1. Check error log
2. Check browser console & network tab
3. Direct query ke database
4. Rollback recent deployment atau fix dengan patch
5. Monitor 24 jam post-fix

**Q**: "Data export ke Excel corrupt. Root cause investigation?"
**Expected Approach**:

- Check Excel file integrity
- Test dengan different export range/file size
- Check memory usage saat export
- Verify script tidak timeout/killed

### B2. Learning & Development

**Q**: "Anda belum pernah pakai fitur X. Bagaimana Anda learn?"
**Expected Answer**:

- Read documentation (official docs, Laravel docs)
- Search & read existing code (GitHub, Stack Overflow)
- Trial & error di dev environment
- Google generative AI, ChatGPT sebagai tutor
- Ask senior developer/mentor
- Tidak langsung push ke production

### B3. Code Quality Awareness

**Q**: "Kode di controller Anda quite long. Refactor ke mana?"
**Expected Answer**:

- Business logic → Model atau Service class
- Validation logic → Form Request
- Complex formatting → View atau Helper
- Background task → Job class

---

# BAGIAN 4: KESALAHAN UMUM YANG HARUS DIHINDARI

## KATEGORI A: KESALAHAN TEKNIS

### Kesalahan #1: Tidak Bisa Tunjuk Kode Spesifik

❌ **Buruk** (yang sering terjadi):

- Asesor: "Di mana di kode ada perulangan?"
- Siswa: "Ada di controller..."
- Asesor: "Tunjuk file dan baris nomor!"
- Siswa: "Hmm, saya lupa..."

✅ **Baik**:

- Langsung membuka file di laptop
- Tunjuk line number, baca kode dengan confident
- "Di `app/Http/Controllers/User/PetaController.php` line 25-51, saya pakai `map()` untuk transform setiap grid..."

**Cara Persiapan**: Hafal file structure & bisa quick search file di VS Code pake Ctrl+P

### Kesalahan #2: Tidak Paham Alasan Pilihan Desain

❌ **Buruk**:

- Asesor: "Mengapa pakai Laravel?"
- Siswa: "Karena laravel populer..."
- Asesor: "Apa advantage Laravel dibanding framework lain?"
- Siswa: "Entah, guru suruh pakai aja..."

✅ **Baik**:

- "Laravel chosen karena: (1) inbuilt authentication sistem; (2) Eloquent ORM untuk relasi database yang mudah; (3) easy middleware untuk role-based access; (4) active community untuk learning resources"

**Cara Persiapan**: Prepare 3-5 alasan untuk setiap teknologi yang dipakai

### Kesalahan #3: Tidak Tahu Database Schema

❌ **Buruk**:

- Asesor: "Jelaskan tabel data_survei!"
- Siswa: "Ada judul, tahun, tipe... dan... entah saya lupa"

✅ **Baik**:

- Siap jawab: "Tabel data_survei ada 15 kolom: id (primary key), judul, ketua_tim, tahun, tipe, wilayah, deskripsi, gambar_pratinjau, gambar_thumbnail, gambar_medium, file_scan_asli, ukuran_file_asli, format_file_asli, diunggah_oleh (FK ke admin), timestamps"

**Cara Persiapan**: Buka migration file, hafal nama & tipe setiap kolom

### Kesalahan #4: Menjelaskan Teori Tanpa Contoh Konkret

❌ **Buruk**:

- Asesor: "Apa itu array di PHP?"
- Siswa: "Array adalah struktur data yang menyimpan kumpulan element..."
- (Asesor mulai mengantuk)

✅ **Baik**:

- "Di sistem saya, di PetaController line 68, saya punya array `$stats` yang menyimpan statistik:
    ```php
    'tipe_data' => [
        '2D' => 5,
        '3D' => 3,
        'HR' => 2
    ]
    ```
    Array ini saya pakai untuk di-loop di view, menampilkan count setiap tipe survei."

### Kesalahan #5: Tidak Paham Error Message Sendiri

❌ **Buruk** (sering terjadi):

- Asesor: "Coba jalankan aplikasi!"
- Siswa: (jalankan, ada error di console)
- Asesor: "Ada error apa?"
- Siswa: "Ada error, tapi saya tidak tahu apa..."
- Asesor: "Baca error message-nya!"
- Siswa: (baca) "Ah... 'Call to undefined method gridKotak'..."

✅ **Baik**:

- Langsung baca error message
- "Error 'Call to undefined method gridKotak'. Artinya di class Model saya, method `gridKotak()` belum didefinisikan. Saya skip untuk sekarang, fix nanti."

**Cara Persiapan**: Laravel error message sangat informatif. Baca baik-baik, jangan panic!

### Kesalahan #6: Tidak Konsisten dengan Dokumentasi Google Form

❌ **Buruk**:

- Di Google Form: "Sistem memiliki fitur export PDF"
- Saat ujikom: "Hmm, fitur PDF saya belum selesai..."
- Asesor: (mencoret poin)

✅ **Baik**:

- Sebelum Google Form: Pastikan fitur sudah jalan 100%
- OR jika belum selesai, jangan claim di Google Form
- Honest & realistic expectations

---

## KATEGORI B: KESALAHAN KOMUNIKASI / SOFT SKILLS

### Kesalahan #7: Berbicara Terlalu Cepat / Terlalu Lamban

❌ **Buruk**:

- Berbicara 200 kata/menit, asesor tidak catch
- Atau berbicara 50 kata/menit, asesor bosan

✅ **Baik** (~120-150 kata/menit):

- Normal conversation speed
- Pause untuk asesor process
- Adjust based on asesor reaction

### Kesalahan #8: Tidak Membaca Body Language Asesor

❌ **Buruk**:

- Asesor makes confused face → Siswa terus cerita panjang
- Asesor mengangguk → Siswa mikir sudah paham, lanjut topic lain

✅ **Baik**:

- Lihat reaksi asesor
- "Apakah sudah cukup jelas? Atau mau saya elaborate lebih detail?"
- Interactive communication, bukan one-way

### Kesalahan #9: Defensive / Argumentative

❌ **Buruk**:

- Asesor: "Kenapa Anda tidak pakai cara yang lebih simple?"
- Siswa: "Tapi cara saya juga benar! Cara itu lebih bagus!"
- (Asesor jadi kesal)

✅ **Baik**:

- "Hmm, perspektif menarik. Memang ada multiple ways. Saya pilih ini karena alasan X, Y, Z. Tapi saya appreciate input Anda untuk kedepannya."

### Kesalahan #10: Tidak Siap Pertanyaan Teknis

❌ **Buruk**:

- Asesor: "Relasi Many-to-Many apa advantages & disadvantages?"
- Siswa: "Hmm... entahlah"

✅ **Baik**:

- Prepare jawaban untuk 20-30 potential technical questions
- Siap 3-5 detik untuk think sebelum jawab, tidak langsung jawab dengan uncertain

---

## KATEGORI C: KESALAHAN PERSIAPAN

### Kesalahan #11: Tidak Test Aplikasi Sebelum Ujikom

❌ **Buruk**:

- Aplikasi sudah 2 minggu tidak dibuka
- Ujikom hari H, buka aplikasi → Error!
- "Hmm, aneh... kemarin jalan..."

✅ **Baik**:

- Hari sebelum ujikom: Test semua fitur
- Fresh install database
- Run aplikasi di laptop sendiri + di Laragon
- Ensure semua berjalan smooth

### Kesalahan #12: Bring Messy Laptop

❌ **Buruk**:

- File desktop berantakan (47 file random)
- Folder project di C:\Users\....\Downloads\Project\Final\FINAL\v2\v3\real_final
- Tidak bisa quick navigate

✅ **Baik**:

- Clean desktop
- Project di folder terstruktur rapi
- Bisa quick open file dengan Ctrl+P di VS Code
- Mouse cursor tidak tersendat (clean laptop performance)

### Kesalahan #13: Tidak Prepare Presentation Materials

❌ **Buruk**:

- Hanya bawa laptop, sambil explain verbally
- Asesor tidak bisa see big picture

✅ **Baik**:

- Prepare slide untuk overview
- Tunjuk ERD database
- Tunjuk system architecture diagram
- Help asesor understand context dengan visual

### Kesalahan #14: Panik Saat Error Muncul

❌ **Buruk**:

- Error muncul saat ujikom
- Siswa jadi gugup, mulai random klik, makin error
- Asesor: "Tidak apa-apa, tenang saja"

✅ **Baik**:

- Calm down
- "Ada error 'Call to undefined method'. Biasa terjadi karena xxx. Saya fix dulu..."
- Show problem-solving mindset, bukan panic

---

## KATEGORI D: KESALAHAN DOKUMENTASI

### Kesalahan #15: Dokumentasi Tidak Update

❌ **Buruk**:

- Dokumentasi tulis fitur X
- Saat ujikom, fitur X sudah berubah significantly
- Dokumentasi jadi misleading

✅ **Baik**:

- Update dokumentasi setiap kali code berubah
- Dokumentasi selalu reflect current state

---

# BAGIAN 5: STRATEGI TERLIHAT PROFESIONAL & MEYAKINKAN

## STRATEGI #1: THE CONFIDENT YET HUMBLE MINDSET

✅ **Confident** (Menunjukkan expertise):

- Direktly tunjuk file di code saat ditanya
- Jelaskan dengan penuh percaya diri
- "Saya designed sistem ini dengan multiple layers..." (bukan "saya coba-coba...")

✅ **Humble** (Siap belajar):

- "Ada cara yang lebih baik? Saya interested untuk tahu"
- Terima feedback dengan open mind
- "Insight Anda bagus, akan saya implementasikan di project berikutnya"

❌ **Avoid**:

- Over-confident (menolak saran asesor)
- Under-confident (merasa semua salah)

---

## STRATEGI #2: TELL A STORY (Jangan Hanya Data Dump)

❌ **Buruk** (Data dump):

- "Di sistem ada 5 tabel, 15 kolom, 3 relasi, 8 controller, 200 line code..."
- Asesor overwhelmed

✅ **Baik** (Tell a story):

- "User membuka aplikasi. Dia lihat peta interaktif dengan grid kotak. Saat klik grid, AJAX call ke backend, fetch data survei yang related ke grid itu. Data tampil di modal popup. User bisa download file atau lihat detail. Di backend, system validate pegawai role, pastikan hanya pegawai internal yang bisa download file asli. Teknologi yang saya pakai: Laravel backend, Leaflet.js peta, MySQL database dengan relasi many-to-many untuk grid-survei mapping."

---

## STRATEGI #3: VISUAL DEMONSTRATION

✅ **Good practice**:

- Buka aplikasi, jalan fitur di depan asesor
- "Lihat di sini, saya type 'peta', hasil search langsung update"
- "Saya click grid ini, data survei muncul di modal"

❌ **Avoid**:

- Hanya tunjuk kode tanpa demo
- Asesor tidak bisa visualize how user experience itu

---

## STRATEGI #4: SHOW THE THOUGHT PROCESS

Jangan langsung \"Ini jawabannya\", tapi show cara pikir:

**Question**: \"Relasi apa yang Anda pakai untuk grid-survei?"

✅ **Baik** (Show thinking):

> "Baik, mari saya pikir... Satu survei bisa covering multiple grid area karena lintasan panjang. Satu grid bisa punya multiple survei dari tahun berbeda. Jadi relasi-nya bidirectional - many-to-many. Bukan one-to-many. Implementasi-nya pakai tabel pivot `grid_seismik` yang link dua foreign key. Laravel Eloquent support ini with `belongsToMany()` method."

❌ **Buruk**:

> "Many-to-many."

Menunjukkan Anda tidak hanya hafal, tapi paham.

---

## STRATEGI #5: HANDLE KESALAHAN DENGAN GRACIEFUL

Jika ada error/bug di ujikom:

✅ **Gracieful Error Handling**:

- Acknowledge error: "Ada error di sini"
- Explain penyebab: "Ini kemungkinan karena xxx"
- Show problem-solving: Buka error message, baca, kalkulasi, fix
- Learn from it: "Pelajaran: saya harusnya test fitur ini sebelum ujikom"

❌ **Buruk**:

- Deny error: "Aneh, kemarin berjalan..."
- Blame: "Ini internet yang lambat" / "Laragon yang error"
- Panic: Random klik-klik

---

## STRATEGI #6: KNOW YOUR DOCUMENTATION BY HEART

Jangan cuma hafal, tapi understand:

Saat ditanya tentang feature X:
❌ **Buruk**: "Uhh... lemme check documentation..." (buka file)
✅ **Baik**: Explain dari kepala sendiri dengan confidence

Dokumentasi itu backup, bukan primary source

---

## STRATEGI #7: VOCABULARY & TERMINOLOGY

Use correct technical terms:

❌ **Buruk**:

- "Grid itu kayak kotak-kotak di peta"
- "Relasi itu nyambung data"

✅ **Baik**:

- "Grid adalah bounding box polygon yang cover suatu area geografis"
- "Relasi Many-to-Many dengan tabel pivot untuk implement data integrity"

Shows professionalism

---

## STRATEGI #8: TIME MANAGEMENT

- Prepared untuk menjawab pertanyaan 60-90 detik
- Jika asesor interrup, stop & respond to question
- Jangan go tangent/off-topic
- Manage eye contact (jangan lihat laptop terus, lihat asesor)

---

## STRATEGI #9: DRESS & PRESENTATION

- Professional attire (bukan casual)
- Grooming rapi
- Posture: duduk tegak, confident body language
- No phone/distraction during assessment

Ini kelihatan sederhana, tapi impression pertama penting

---

## STRATEGI #10: PREPARE \"GOTCHA\" QUESTIONS

Asesor sering ask curve-balls:

**Q**: "Bagaimana Anda scale sistem ini untuk 1 juta users?"
**Expected thought**: "Database optimization, caching, microservices, load balancing..." (Anda tidak perlu implement, tapi tahu konsepnya)

**Q**: "Bagaimana Anda handle concurrency kalau 2 admin assign survei ke grid yang sama secara bersamaan?"
**Answer idea**: "Pakai database transaction untuk atomic operation. Atau optimistic/pessimistic locking..."

Prepare jawaban untuk edge cases

---

# BAGIAN 6: CHECKLIST FINAL PERSIAPAN

## MINGGU SEBELUM UJIKOM

### Checklist Teknis

- [ ] Test semua fitur aplikasi (katalog, peta, search, filter, export, upload, download)
- [ ] Fresh install database dan test migration
- [ ] Clear cache, clear logs
- [ ] Test di browser berbeda (Chrome, Firefox, Edge)
- [ ] Test di mobile view (responsive design)
- [ ] Verify all routes di `routes/web.php`
- [ ] Run test suite: `php artisan test`
- [ ] Check code untuk syntax errors: `php -l app/Http/Controllers/**/*.php`
- [ ] Verify documentation matches actual code
- [ ] Commit final code ke git

### Checklist Pengetahuan

- [ ] Hafal folder structure dan bisa quick navigate
- [ ] Pahami ERD database sepenuhnya
- [ ] Tiru draw ERD diagram tanpa referensi
- [ ] Prepare verbal explanation untuk setiap unit kompetensi (masing-masing 2-3 menit)
- [ ] Siap jawab 20+ potential questions (lihat sample questions di atas)
- [ ] Dokumentasi dibaca ulang dan pahami sepenuhnya
- [ ] Practice verbal presentation di depan cermin / teman

### Checklist Persiapan Materi

- [ ] Prepare slide PowerPoint (overview sistem, tech stack, fitur, database)
- [ ] Print ERD diagram sebagai handout
- [ ] Print source code highlights (fitur-fitur penting)
- [ ] Siapkan external hardrive backup kode
- [ ] Screenshot error messages yang pernah terjadi + solusinya (buat case study)

### Checklist Laptop & Hardware

- [ ] Backup semua file
- [ ] Clean desktop (hapus file random)
- [ ] Defrag/optimize laptop
- [ ] Update VS Code Extensions
- [ ] Test Laragon startup - bisa buka aplikasi dalam 30 detik?
- [ ] Charge laptop penuh
- [ ] Download required software offline installer (Laragon, VS Code, Chrome)
- [ ] Test mouse & keyboard

### Checklist Soft Skills

- [ ] Bahasa Indonesia: practice speaking dengan jelas & confident
- [ ] Bahasa Inggris: siap bahasa Inggris jika asesor interview dalam English
- [ ] Simulasi ujikom dengan teman/guru (mock interview)
- [ ] Prepare answer untuk 5 behavioral questions (personal project story, overcome challenge, team experience, dll)

---

## HARI SEBELUM UJIKOM

- [ ] Light review code (jangan overthink)
- [ ] Tidur yang cukup (7-8 jam)
- [ ] Persiapan fisik & mental (relax, jangan stress)
- [ ] Riview slide presentation sekali lagi
- [ ] Atur alarm 1 jam sebelum ujikom

---

## HARI UJIKOM - 1 JAM SEBELUM

- [ ] Tiba di lokasi ujikom 15 menit lebih awal
- [ ] Charge laptop (jika tidak penuh)
- [ ] Pergi ke toilet (jangan dalam ujikom)
- [ ] Drink water, take deep breath
- [ ] Review 5 poin utama sistem yang akan dibehas

---

## HARI UJIKOM - SAAT START

✅ **Opening (2-3 menit)**:

- Greet asesor professionally
- "Terima kasih sudah memberi kesempatan. Saya akan present Sistem Informasi Data Peta Seismik. Saya fokus ke 8 unit kompetensi LSP. Ada pertanyaan?"

✅ **Body (40-50 menit)**:

- Present overview & demo aplikasi (10 menit)
- Deep dive ke 8 unit kompetensi (30-40 menit)
- Answer asesor questions (ongoing)

✅ **Closing (2-3 menit)**:

- Summarize
- "Apakah ada yang ingin saya clarify? Terima kasih sudah mengevaluasi saya."

---

# BAGIAN 7: SKENARIO INTERVIEW SIMULASI

## SIMULASI #1: OPENING STRONG

**Asesor**: "Coba perkenalkan diri dan proyekmu!"

**Jawaban Baik** (2 menit):

> "Salam, saya [nama]. Saya siswa SMK jurusan [jurusan], sekarang akan ikut Ujikom LSP Junior Coder.
>
> Proyek saya adalah **Sistem Informasi Data Peta Seismik** untuk digitalisasi arsip data survei seismik kelautan. Ini project real untuk [klien/institusi].
>
> Sistem ini punya beberapa role: Admin mengelola data survei dan assign ke peta grid, Pegawai internal bisa download file survei, dan Publik bisa lihat katalog dan peta interaktif.
>
> Tech stack yang saya pakai:
>
> - **Backend**: Laravel 12 framework (PHP 8.2)
> - **Frontend**: Blade template, Vanilla JavaScript, Leaflet.js (untuk interactive map)
> - **Database**: MySQL dengan relasi Many-to-Many
> - **Tools**: VS Code, Postman, Laravel Debugbar
>
> Saya fokus di beberapa fitur:
>
> 1. **Real-time peta interaktif** dengan system grid spasial
> 2. **Dual authentication** - separate login untuk admin & pegawai
> 3. **Export PDF & Excel** untuk reporting
> 4. **File security** - dokumentasi hanya accessible untuk pegawai internal
>
> Oke, mari kita mulai deep dive. Ingin saya start dengan overview sistem atau langsung ke 8 unit kompetensi?"

**Apa yang dilihat asesor**:

- ✅ Professional introduction
- ✅ Tahu project scope
- ✅ Tech stack spesifik
- ✅ Confident & organized

---

## SIMULASI #2: UNIT 1 - STRUKTUR DATA

**Asesor**: "Jelaskan struktur data di sistem Anda! Berikan contoh relasi Many-to-Many."

**Jawaban Baik** (3 menit):

> "Baik, struktur data sistemnya ada 5 tabel utama. Mari saya gambar ERD-nya:
>
> [Tunjuk ke slide/papan tulis]
>
> ```
> Admin (1) ──→ (∞) DataSurvei
>                      ↓
>                      ↓ (Many-to-Many)
>                      ↓
>                GridKotak ← → DataSurvei
>                      ↓
>            grid_seismik (Pivot)
> ```
>
> **Relasi Many-to-Many** antara DataSurvei dan GridKotak yang saya pakai:
>
> Scenario: Satu survei seismik itu panjang, spanning beberapa area geografis. Jadi satu survei bisa berada di banyak grid kotak. Sebaliknya, satu grid kotak bisa berisi survei dari berbagai tahun (tumpang tindih). Ini adalah classic many-to-many.
>
> **Implementasi di Database**:
>
> - Tabel `data_survei`: menyimpan data survei (id, judul, tahun, tipe, wilayah, deskripsi, dll). PK: id
> - Tabel `grid_kotak`: menyimpan grid area (id, nomor_kotak, bounds_sw_lat, bounds_sw_lng, bounds_ne_lat, bounds_ne_lng, dll). PK: id
> - Tabel `grid_seismik` (Pivot): menghubungkan survei & grid. FK: grid_kotak_id, data_survei_id. Unique key: (grid_kotak_id, data_survei_id)
>
> **Implementasi di Laravel ORM** (Model):
> Di file `app/Models/DataSurvei.php`:
>
> ```php
> public function gridKotak(): BelongsToMany {
>     return $this->belongsToMany(
>         GridKotak::class,
>         'grid_seismik',
>         'data_survei_id',
>         'grid_kotak_id'
>     );
> }
> ```
>
> **Usage di Controller**:
>
> ```php
> // Get survei dan semua grid yang terkait
> $survei = DataSurvei::with('gridKotak')->find(1);
> foreach ($survei->gridKotak as $grid) {
>     echo $grid->nomor_kotak; // Output: 1219, 1220, 1320, etc
> }
>
> // Assign survei ke grid baru
> $survei->gridKotak()->attach([1219, 1220, 1320]);
> ```
>
> **Keuntungan pilihan ini**:
>
> - Data integrity: satu survei tidak duplicate di multiple grid
> - Query efficient: bisa ambil semua survei dalam grid dengan single join
> - Flexible: mudah add/remove survei dari grid tanpa delete data survei
>
> Alternatif yang tidak saya pakai:
>
> - One-to-Many: Kurang flexible, tidak bisa one survei di multiple grid
> - Denormalization: Simpan semua grid ID dalam single column → akan corrupt dengan scale up
>
> Jadi Many-to-Many adalah pilihan yang paling tepat untuk use case ini."

**Apa yang dilihat asesor**:

- ✅ Paham relasi Many-to-Many
- ✅ Bisa tunjuk implementasi di kode
- ✅ Tahu alasan pilihan & trade-off
- ✅ Clear explanation dengan contoh konkret

---

## SIMULASI #3: UNIT 3 - PERCABANGAN & PERULANGAN

**Asesor**: "Tunjukkan where di kode Anda ada percabangan (if-else) dan jelaskan logikanya!"

**Jawaban Baik**:

> "Okay, mari saya tunjuk contoh percabangan di kode.
>
> [Buka VS Code, Ctrl+P, search 'KatalogController.php']
>
> Di sini, line 22-45, ada multiple if-statements untuk filtering:
>
> ```php
> if ($request->filled('search')) {
>     $query->where(function ($q) use ($search) {
>         $q->where('judul', 'LIKE', '%' . $search . '%')
>             ->orWhere('deskripsi', 'LIKE', '%' . $search . '%');
>     });
> }
> if ($request->filled('tahun')) {
>     $query->where('tahun', $request->tahun);
> }
> if ($request->filled('tipe')) {
>     $query->where('tipe', $request->tipe);
> }
> ```
>
> **Logika**: Setiap if check, apakah user input ada parameter tersebut. Jika ada, tambah kondisi WHERE ke query. Contohnya:
>
> - User search 'peta' → tambah condition `WHERE judul LIKE '%peta%' OR deskripsi LIKE '%peta%'`
> - User filter tahun 2023 → tambah condition `WHERE tahun = 2023`
> - User filter tipe '3D' → tambah condition `WHERE tipe = '3D'`
> - User bisa combine: search + tahun + tipe → semua condition merged dengan AND
>
> Kalau semua parameter kosong, query return semua survei tanpa filter.
>
> **Alternative implementation**: Saya bisa pakai switch-case, tapi if-statements lebih readable di kasus ini.
>
> Sekarang contoh **perulangan**. [Buka PetaController.php, line 25-50]
>
> Di sini ada **nested loop** - loop dalam loop:
>
> ```php
> $grids = $gridsData->map(function ($grid) {
>     return [
>         'id' => $grid->id,
>         'survei_list' => $grid->dataSurvei->map(function ($survei) {
>             return [
>                 'id' => $survei->id,
>                 'judul' => $survei->judul,
>             ];
>         })->toArray(),
>     ];
> });
> ```
>
> **Logika**:
>
> - Outer loop: Iterator setiap grid kotak (mungkin 5000 grid)
> - Inner loop: Untuk setiap grid, iterator semua survei yang ada di dalam grid itu (mungkin 1-10 survei per grid)
> - Transform data: Convert ke format JSON yang compatible dengan Leaflet.js di frontend
>
> Result: Array struktur nested, siap di-pass ke view & di-loop di Blade template untuk render peta."

---

## SIMULASI #4: DEBUGGING SKENARIO

**Asesor**: "Bayangkan user report: 'Grid 1219 tidak show data survei'. Bagaimana Anda debug?"

**Jawaban Baik** (systematic approach):

> "Okay, debugging ini kita lakukan step-by-step.
>
> **Step 1: Reproduce the issue** (buka aplikasi)
> Saya buka aplikasi, navigate ke peta, cari grid 1219. Memverify bahwa emang tidak ada data yang tampil.
>
> **Step 2: Check Browser Console (F12)**
> [Tekan F12, buka Console tab]
> Lihat ada JavaScript error? Ada AJAX error? Atau request lancar tapi response kosong?
>
> **Step 3: Check Network tab**
> Lihat request ke `/api/grid/1219` atau endpoint apapun.
>
> - Status: 200? atau 404?
> - Response body: Ada data survei atau kosong?
>
> **Step 4: Check Laravel Log**
> [Buka terminal, `tail -f storage/logs/laravel-xxx.log`]
> Ada error message? Exception? Atau quiet (tidak ada error)?
>
> **Step 5: Direct Database Query**
> [Buka MySQL terminal]
>
> ```sql
> SELECT * FROM grid_kotak WHERE id = 1219;
> ```
>
> Grid ada? Data ada?
>
> ```sql
> SELECT * FROM grid_seismik WHERE grid_kotak_id = 1219;
> ```
>
> Relasi ada? Ada data survei yang di-assign ke grid ini?
>
> **Step 6: Hypothesize & Test**
> Based on findings, possible causes:
>
> - Database record kosong: Perlu assign survei ke grid dulu via admin panel
> - SQL query wrong: Check controller logic pakai debugbar
> - Frontend rendering issue: Check Blade template logic
> - Geocoordinate issue: Grid bounds tidak valid, tidak render di map
>
> **Step 7: Fix & Verify**
> Implement fix. Terus retest:
>
> - Refresh page (Ctrl+Shift+Del hard refresh)
> - Clear browser cache
> - Grid 1219 now showing data? ✓ FIXED
>
> **Lesson learned**: Saya dokumentasikan ini case di internal knowledge base untuk reference future."

---

## SIMULASI #5: BEST PRACTICES

**Asesor**: "Bagaimana Anda handle security untuk file download?"

**Jawaban Baik**:

> "Security untuk file download critical, terutama jika file itu sensitive (scan dokumen asli).
>
> **Issue**: File tidak boleh accessible oleh publik. Hanya pegawai internal yang bisa download.
>
> **Solution yang saya implementasikan**:
>
> **1. File Storage** (Backend):
>
> - File `file_scan_asli` saya simpan di folder yang **NOT accessible via URL** (disk 'private').
> - Tidak pakai `public/` folder yang bisa di-access langsung.
>
> ```php
> $path = $file->storeAs('scan_asli', $fileName, 'public'); // Simpan ke private storage
> ```
>
> **2. Access Control** (Middleware):
>
> - Route `/download/{id}` protected dengan middleware `'pegawai'`.
> - Hanya user yang authenticated sebagai pegawai yang bisa access route ini.
>
> ```php
> Route::get('/download/{id}', [ScanDownloadController::class, 'download'])
>     ->middleware('pegawai');
> ```
>
> **3. Verification di Controller**:
>
> - Sebelum download, verify bahwa pegawai ini `is_approved` oleh admin.
> - Check apakah survei ID valid.
> - Log download activity untuk audit trail.
>
> ```php
> public function download($id) {
>     $survei = DataSurvei::findOrFail($id);
>
>     if (!Auth::guard('pegawai')->user()->is_approved) {
>         abort(403, 'Akun belum disetujui');
>     }
>
>     Log::info('File downloaded', ['survei_id' => $id, 'pegawai' => Auth::id()]);
>
>     return Storage::download($survei->file_scan_asli);
> }
> ```
>
> **4. Input Validation**:
>
> - Validate bahwa ID parameter adalah integer, bukan string injection.
>
> **Result**:
>
> - File protected dari unauthorized access
> - Audit trail tercatat
> - Transparent untuk user (download berfungsi normal)
>
> This adalah implementasi dari concept **principle of least privilege** - hanya beri akses yang dibutuhkan, tidak lebih."

---

# RINGKASAN QUICK REFERENCE

## 8 Unit Kompetensi (Poin-Poin Kunci)

| Unit                       | Fokus                                      | Contoh di Proyek Anda                                                 |
| -------------------------- | ------------------------------------------ | --------------------------------------------------------------------- |
| **1. Struktur Data**       | Many-to-Many relasi, array transform       | grid_seismik pivot table, stats array di PetaController               |
| **2. Spesifikasi Program** | Framework choice, tech stack, requirements | Laravel dipilih karena auth/ORM/middleware, dual-guard implementation |
| **3. Perintah Eksekusi**   | if-else, loops, multimedia                 | Filter logic, map() transform, image upload & PDF export              |
| **4. Best Practices**      | DRY, naming, sanitasi                      | Form Request, HtmlSanitizerService, readableコード                    |
| **5. Tersturuktur**        | MVC, modular                               | Controller orchestrate, Model query, Service business logic           |
| **6. Dokumentasi**         | PHPDoc, README, comments                   | Function documentation, inline comments, DOKUMENTASI_TEKNIS.md        |
| **7. Debugging**           | Tools & systematic approach                | dd(), logs, DevTools, MySQL query, Laravel Debugbar                   |
| **8. Testing**             | Black/White/Grey box, PHPUnit              | Test cases untuk search, filter, upload, database integrity           |

---

## Vocabulary Penting (Gunakan di Ujikom)

- **Eloquent ORM**: Object-Relational Mapping - cara Laravel interact dengan database
- **Middleware**: Software layer yang filter request sebelum reach controller
- **Pivot Table**: Tabel penghubung di relasi many-to-many
- **Eager Loading**: Query optimization dengan `with()` untuk prevent N+1 issue
- **Service Class**: Business logic layer, separasi dari controller
- **Route Guard**: Auth guard untuk multiple authentication systems
- **CSRF Protection**: Security feature untuk prevent cross-site request forgery
- **Validation**: Input checking dari user
- **Sanitization**: Cleaning data untuk prevent injection attacks

---

## Persiapan Final Sebelum Ujikom

1. **Install Laragon** - Server lokal terbaru
2. **Clone/setup project** - Fresh database, run migrations
3. **Test semua fitur** - Pastikan berjalan normal
4. **Dokumentasi update** - Sesuai dengan current code
5. **Slide preparation** - ERD, architecture, fitur overview
6. **Mock interview** - Simulasi ujikom dengan teman/guru
7. **Sleep well** - Hari sebelum ujikom + hari ujikom
8. **Arrive early** - 15 menit sebelum ujikom

---

Semoga panduan ini membantu persiapan Ujikom Anda! Saya yakin dengan persiapan matang ini, Anda akan terlihat profesional dan confident di depan asesor.

Good luck! 🚀

---

**Catatan Penting**:

- Dokumentasi ini untuk persiapan comprehensive. Tidak perlu hafal semua, tapi understand konsep utamanya.
- Ada pertanyaan? Tanya asesor / guru saat persiapan, jangan saat ujikom.
- Tunjukkan learning mindset - tidak ada yang perfect, yang penting show improvement & problem-solving.
````
