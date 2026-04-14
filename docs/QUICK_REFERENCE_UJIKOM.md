# QUICK REFERENCE - UJIKOM PREPARATION

## Cheat Sheet untuk Persiapan & Hari Ujikom

---

## 1. PROJECT QUICK FACTS (Hafal!)

**Nama Proyek**: Sistem Informasi Data Peta Seismik (SIMAP)
**Pemilik**: BBSPGL (Balai Besar Survei Pemetaan Geologi Laut)
**Tujuan**: Digitalisasi arsip survei seismik konvensional → peta interaktif

### Tech Stack

```
Backend      : Laravel 12 + PHP 8.2
Frontend     : Blade + Vanilla JS + Leaflet.js (OpenStreetMap)
Database     : MySQL 8.0 dengan Relation Many-to-Many
Server       : Apache/Nginx (Laragon)
Deployment   : Local Development
```

### 5 Database Tables

```
1. admin              → User management rol admin
2. data_survei        → Core data survei seismik (15 kolom)
3. grid_kotak         → Sistem grid spasial peta (12 kolom)
4. grid_seismik       → Pivot table relasi many-to-many
5. pegawai_internal   → User role pegawai internal (untuk download file)
```

### Key Features

```
✓ Interactive peta dengan Leaflet.js
✓ Katalog survei dengan search/filter/sort
✓ Admin CRUD data survei & assign ke grid
✓ Pegawai bisa download file asli (secure)
✓ Export PDF laporan & Excel spreadsheet
✓ Dual authentication (admin vs pegawai)
✓ Role-based access control
✓ Image optimization (thumbnail, medium, full size)
✓ CSRF protection & input sanitasi
```

---

## 2. 8 UNIT KOMPETENSI - 30-SECOND ELEVATOR PITCH

### Unit 1: Menggunakan Struktur Data

**Pitch**: "Pakai relasi Many-to-Many antara DataSurvei dan GridKotak via tabel pivot grid_seismik. Satu survei bisa di banyak grid, satu grid bisa punya banyak survei. Array dipakai untuk transform data ke format JSON sebelum kirim ke frontend."

### Unit 2: Menggunakan Spesifikasi Program

**Pitch**: "Laravel dipilih karena inbuilt authentication, Eloquent ORM untuk relasi, middleware untuk keamanan. Tech stack: PHP 8.2, MySQL, Blade. Requierment: admin manage data, pegawai download file, publik lihat peta. Spesifikasi keamanan: dual-guard, HTML sanitasi, CSRF token, role-based middleware."

### Unit 3: Menerapkan Perintah Eksekusi

**Pitch**: "Percabangan: if-statements di KatalogController untuk multiple filter (search, tahun, tipe, wilayah). Match expression untuk sorting. Try-catch untuk email error handling. Perulangan: map() di PetaController untuk transform grid data ke format JSON, nested loop untuk setiap survei dalam grid. Multimedia: upload image (validated, random filename), queue background job untuk resize thumbnail, export PDF dengan DomPDF, export Excel dengan PhpSpreadsheet."

### Unit 4: Best Practices & Guidelines

**Pitch**: "Naming: camelCase variable, PascalCase class. DRY: Form Request untuk validation rules (tidak copy-paste). Comment: PHPDoc di function, inline comment untuk complex logic. Keamanan: validasi input, sanitasi HTML, query binding auto-prevent SQL injection. Modular: separasi logic di Service, upload di Job, bukan di controller."

### Unit 5: Pemrograman Terstruktur

**Pitch**: "MVC pattern: Model (query DB), Controller (orchestrate), View (render HTML). Request → Router → Controller → Model → View → HTML. Folder terstruktur: Models/, Controllers/Admin, Controllers/User, Services/, Jobs/. Modular reusability: HtmlSanitizerService dipakai di multiple controller. Single responsibility: setiap class punya one job."

### Unit 6: Membuat Dokumentasi

**Pitch**: "PHPDoc di setiap function (parameter, return value). Inline comment untuk complex logic. README.md untuk project overview. DOKUMENTASI_TEKNIS_LENGKAP.md untuk database schema, teknologi, alur logika. Database migration comments explain setiap tabel. Comment maintain updated setiap code change."

### Unit 7: Debugging

**Pitch**: "Systematic: read error message → check logs (tail -f storage/logs/) → DevTools network/console → MySQL direct query → Laravel Debugbar → fix → test ulang. Tools: dd(), dump(), Log::info(), DevTools (F12), Debugbar, MySQL CLI. Common bugs: undefined method (typo), null variable (check with if), SQLSTATE (SQL error), permission denied (chmod), too many redirect (check logic)."

### Unit 8: Pengujian Unit Program

**Pitch**: "Black Box: test from user perspective (search work? filter work? export work?). White Box: test internal logic (validation work? query correct? relasi link data?). Grey Box: hybrid. PHPUnit test case cover semua branch path. Run dengan `php artisan test`. Target: ≥80% code coverage."

---

## 3. COMMON QUESTION - 30-SECOND ANSWERS

### Q: \"Jelaskan alur user click grid di peta sampai data tampil!\"

**A**:

> "User buka /peta, PetaController query semua GridKotak dengan relasi DataSurvei pakai `with()`, transform data ke JSON format Leaflet, render view dengan peta. Frontend Leaflet.js render grid area sebagai polygon. User click grid → trigger JavaScript onclick handler → AJAX POST ke /api/grid/{id} → Controller return data survei dalam JSON → JavaScript update modal popup → tampil detail survei."

### Q: \"Kenapa pakai Many-to-Many?\"

**A**:

> "Satu survei panjang bisa spanning banyak grid area, satu grid bisa punya survei dari banyak tahun. Many-to-many di database = flexibility. Implementasi = tabel pivot grid_seismik yang reference kedua tabel. Laravel Eloquent: `belongsToMany()` method."

### Q: \"Di mana kode untuk search?\"

**A**:

> "KatalogController.php line 22-31. Ada `if ($request->filled('search'))` yang add WHERE condition. Pakai LIKE wildcard untuk partial match. Bisa search judul, deskripsi, tipe, wilayah, ketua_tim."

### Q: \"Bagaimana Anda handle file security?\"

**A**:

> "File scan_asli tidak di public folder (tidak accessible URL). Route protected middleware 'pegawai'. Controller verify user is_approved. Query DB check survei exists. Log download activity untuk audit. Return download via Storage::download()."

### Q: \"Error terjadi, gimana?\"

**A**:

> "Pertama, read error message. Lihat file & line number. Cek Laravel log (tail -f). Check DevTools network. Cek database. Kalau masih unclear, dd() variable untuk debug. Fix. Test ulang. Done."

### Q: \"Difference between array dan collection?\"

**A**:

> "Array = basic PHP structure. Collection = Laravel enhanced array dengan methods seperti map(), filter(), groupBy(). Pakai collection ketika data dari database yang perlu manipulasi."

### Q: \"Bagaimana optimize query?\"

**A**:

> "Eager loading with `with()` prevent N+1 query. Pagination limit data returned. Index database kolom yang sering di-filter. Avoid LIKE '%. . .%' (full wildcard). Cache query hasil jika sering diakses."

### Q: \"Apa itu middleware?\"

**A**:

> "Middleware = filter layer antara request & controller. Contoh: `pegawai` middleware verify user is pegawai internal sebelum allow akses protected route. Laravel middleware built-in: auth, csrf, role check."

---

## 4. DATABASE SCHEMA QUA - HAFAL KOLOM!

### Tabel: data_survei (15 kolom)

```
id                 INT PRIMARY KEY
judul              VARCHAR(255) - nama kegiatan survei
ketua_tim          VARCHAR(255) - kepala tim
tahun              YEAR - tahun pelaksanaan
tipe               ENUM('2D', '3D', 'HR')
wilayah            VARCHAR(255) - lokasi geografis
deskripsi          LONGTEXT - penjelasan detail
gambar_pratinjau   VARCHAR(255) - path file cover yang displayed di katalog
gambar_thumbnail   VARCHAR(255) - thumbnail size (optimize loading)
gambar_medium      VARCHAR(255) - medium size (untuk peta)
file_scan_asli     VARCHAR(255) - path file original (secure, untuk pegawai)
ukuran_file_asli   BIGINT - file size dalam bytes
format_file_asli   VARCHAR(20) - extension: pdf, tiff, png, dll
diunggah_oleh      INT FK → admin.id (siapa yang upload)
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

### Tabel: grid_kotak (12 kolom)

```
id                 INT PRIMARY KEY
nomor_kotak        VARCHAR(50) UNIQUE - ID grid (misal "1219")
bounds_sw_lat      DECIMAL(10,6) - Southwest latitude
bounds_sw_lng      DECIMAL(10,6) - Southwest longitude
bounds_ne_lat      DECIMAL(10,6) - Northeast latitude
bounds_ne_lng      DECIMAL(10,6) - Northeast longitude
center_lat         DECIMAL(10,6) - Center point latitude
center_lng         DECIMAL(10,6) - Center point longitude
geojson_polygon    LONGTEXT - GeoJSON untuk rendering Leaflet
status             ENUM('empty', 'filled') - ada data atau kosong
total_data         INT - count survei dalam grid ini
created_at         TIMESTAMP
updated_at         TIMESTAMP
```

### Tabel: grid_seismik (Pivot Table)

```
id                 INT PRIMARY KEY
grid_kotak_id      INT FK → grid_kotak.id
data_survei_id     INT FK → data_survei.id
assigned_by        INT FK → admin.id (siapa yang assign)
assigned_at        TIMESTAMP - kapan di-assign
created_at         TIMESTAMP
updated_at         TIMESTAMP

UNIQUE KEY: (grid_kotak_id, data_survei_id)
```

---

## 5. CODE SNIPPETS - COPY PASTE KE JAWABAN

### Snippet 1: Many-to-Many Implementasi

```php
// Model: DatabaseSurvei.php
public function gridKotak(): BelongsToMany {
    return $this->belongsToMany(
        GridKotak::class,
        'grid_seismik',
        'data_survei_id',
        'grid_kotak_id'
    )->withPivot(['assigned_by', 'assigned_at']);
}
```

### Snippet 2: Search dengan If-Statements

```php
// KatalogController.php line 22-44
if ($request->filled('search')) {
    $search = $request->search;
    $query->where(function ($q) use ($search) {
        $q->where('judul', 'LIKE', '%' . $search . '%')
            ->orWhere('deskripsi', 'LIKE', '%' . $search . '%');
    });
}
```

### Snippet 3: Nested Loop Transform

```php
// PetaController.php line 25-51
$grids = $gridsData->map(function ($grid) {
    return [
        'id' => $grid->id,
        'survei_list' => $grid->dataSurvei->map(function ($survei) {
            return [
                'id' => $survei->id,
                'judul' => $survei->judul,
            ];
        })->toArray(),
    ];
});
```

### Snippet 4: Try-Catch Error Handling

```php
// KontakController.php line 37-46
try {
    Mail::to($recipients)->send(new ContactFormMail($validated));
    return back()->with('success', 'Pesan berhasil dikirim!');
} catch (\Exception $e) {
    return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
}
```

### Snippet 5: Secure File Download

```php
// ScanDownloadController.php
public function download($id) {
    $survei = DataSurvei::findOrFail($id);

    if (!Auth::guard('pegawai')->user()->is_approved) {
        abort(403, 'Tidak authorized');
    }

    Log::info('Download file', ['survei_id' => $id]);
    return Storage::download($survei->file_scan_asli);
}
```

---

## 6. FOLDER NAVIGATION - QUICK FIND

```
app/
├── Models/
│   ├── DataSurvei.php          ← Many-to-Many relasi
│   ├── GridKotak.php           ← Grid model
│   ├── Admin.php               ← Auth guard 1
│   └── PegawaiInternal.php     ← Auth guard 2
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DataSurveiController.php  ← CRUD survei (line 1-295)
│   │   │   ├── ExportController.php      ← PDF/Excel export (line 1-473)
│   │   │   └── GridKotakController.php   ← Manage grid assignment
│   │   └── User/
│   │       ├── KatalogController.php     ← Search/filter/sort (line 22-44!)
│   │       ├── PetaController.php        ← Map logic (line 25-51!)
│   │       └── KontakController.php      ← Email form, try-catch error
│   ├── Requests/
│   │   └── DataSurveiRequest.php        ← Form validation
│   └── Middleware/
│       ├── AdminMiddleware.php          ← Guard admin
│       └── PegawaiMiddleware.php        ← Guard pegawai
├── Services/
│   ├── HtmlSanitizerService.php         ← Sanitasi input
│   └── ImageOptimizationService.php     ← Resize image
└── Jobs/
    └── ProcessSurveiImage.php           ← Background job thumbnail

database/
├── migrations/
│   ├── create_data_survei_table.php
│   ├── create_grid_kotak_table.php
│   └── create_grid_seismik_table.php
└── seeders/
    └── DatabaseSeeder.php

routes/
└── web.php                              ← Semua route di sini

resources/
└── views/
    ├── admin/
    │   ├── data_survei/
    │   │   ├── index.blade.php          ← List survei dengan search/filter
    │   │   ├── create.blade.php         ← Form create
    │   │   ├── edit.blade.php           ← Form edit
    │   │   └── show.blade.php           ← Detail survei
    │   └── layouts/
    │       └── app.blade.php            ← Admin layout
    └── user/
        ├── peta/
        │   └── index.blade.php          ← Peta interaktif Leaflet.js
        ├── katalog/
        │   ├── index.blade.php          ← Katalog dengan grid display
        │   └── show.blade.php           ← Detail survei
        └── layouts/
            └── app.blade.php            ← User layout
```

**Quick Open File (Ctrl+P)**:

- `KatalogController` → Search/filter logic
- `PetaController` → Map & nested loop
- `DataSurveiController` → Upload & CRUD
- `ExportController` → PDF/Excel export
- `data_survei.php` (Model) → Many-to-Many relasi
- `grid_seismik` (migration) → Pivot table structure

---

## 7. COMMON MISTAKES - AVOID THESE!

❌ **Tidak bisa tunjuk kode saat ditanya**
→ Familiarize dengan file structure, practice Ctrl+P search

❌ **Tidak tahu alasan pilihan teknologi**
→ Siap 3 alasan untuk Laravel, MySQL, Leaflet.js, dll

❌ **Tidak bisa jelaskan error message**
→ Read error message carefully! File & line number ada di dalamnya!

❌ **Berbicara terlalu cepat / terlalu pelan**
→ Practice speaking 120-150 word/menit. Pause untuk asesor process.

❌ **Tidak siap edge case / curveball questions**
→ Prepare jawaban untuk 20+ potential questions (lihat Bagian 2 panduan)

❌ **Aplikasi full of bugs saat ujikom**
→ Test TesT TEEEST! Sehari sebelum ujikom, coba semua fitur!

❌ **Defensive saat asesor kebeturan menemukan masalah**
→ Calm. Acknowledge. Debug. Fix. Learn. Jangan blame.

❌ **Tidak bisa navigate ke file sendiri**
→ Kenali folder structure & bisa quick find dengan VS Code shortcuts.

---

## 8. HARDWARE CHECKLIST - BAWA KE UJIKOM

```
☑ Laptop (fully charged, tested start-up time < 30 sec)
☑ Mouse & Keyboard (backup in case laptop built-in error)
☑ External SSD/HDD (backup kode)
☑ Power adapter (charge laptop 1 jam sebelum)
☑ Headphone (jika ada demo video)
☑ Dokumentasi printout (ERD, code highlights)
☑ Slide PowerPoint (overview sistem, tech stack, fitur)
☑ Notes (30-second answers untuk tiap unit)
☑ Laragon installed & tested (dapat buka aplikasi 30 detik)
☑ VS Code dengan extensions ready (PHP, Blade, MySQL)
☑ Browser (Chrome/Firefox)
☑ Database restored (fresh migration, seeders siap)
```

---

## 9. MORNING CHECKLIST - HARI UJIKOM

**1 Jam Sebelum Ujikom**:

- [ ] Laptop fully charged
- [ ] Laragon start, test peta & katalog buka lancar
- [ ] Database fresh (migration done)
- [ ] Open VS Code, familiarize dengan file structure
- [ ] Review 5 poin utama yang akan di-discuss
- [ ] Toilet break
- [ ] Drink water, deep breathe
- [ ] Positive mindset!

**15 Menit Sebelum Ujikom**:

- [ ] Arrive at location
- [ ] Greet asesor professionally
- [ ] Setup laptop & mouse if needed
- [ ] Smile & eye contact
- [ ] Ready to rock! 💪

---

## 10. PRESENTATION STRUCTURE (50 Menit Total)

| Waktu     | Activity                                 | Durasi |
| --------- | ---------------------------------------- | ------ |
| 0-3 min   | Greeting & introduction sistem           | 3 min  |
| 3-13 min  | Demo aplikasi (peta, katalog, admin)     | 10 min |
| 13-53 min | Discuss 8 unit kompetensi (5-6 min each) | 40 min |
| 53-60 min | Q&A, closing                             | 7 min  |

**Tips**:

- Jangan jelasin lebih dari 7 menit per unit → asesor might interrupt
- Interactive: tanya asesor "ada pertanyaan apakah sudah cukup jelas?"
- Time management: jangan go off-topic

---

Semoga quick reference ini membantu! Print atau bookmark untuk easy access saat persiapan ujikom. Good luck! 🍀
