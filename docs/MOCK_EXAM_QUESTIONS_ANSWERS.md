# MOCK EXAM QUESTIONS & MODEL ANSWERS

## Simulasi Ujikom LSP Junior Coder - Data Peta Seismik

---

## BAGIAN I: UNIT 1 - MENGGUNAKAN STRUKTUR DATA

### Question 1.1 (Mudah)

**Asesor**: "Jelaskan 5 tabel di database Anda dan fungsi masing-masing!"

**Model Answer** (2 menit):

> **Tabel 1: admin**
> Menyimpan data administrator yang manage sistem. Kolom utama: id (PK), nama, email (unique), kata_sandi (hashed).
>
> **Tabel 2: data_survei**
> Core data - menyimpan informasi detail survei seismik. Kolom: id, judul, tahun, tipe (2D/3D/HR), wilayah, deskripsi, gambar dalam 3 versi (pratinjau/thumbnail/medium), file scan asli untuk pegawai, diunggah_oleh (FK ke admin), timestamps. Total 15 kolom.
>
> **Tabel 3: grid_kotak**
> Sistem spasial - menyimpan kotak-kotak di peta. Nomor_kotak (unique ID), bounds (SW & NE coordinates untuk bounding box), center coordinates (untuk marker modal), GeoJSON polygon, status (empty/filled), total_data count. Total 12 kolom.
>
> **Tabel 4: grid_seismik** (Pivot)
> Menghubungkan relasi many-to-many antara data_survei dan grid_kotak. Foreign key ke kedua tabel. Unique constraint (grid_kotak_id, data_survei_id) untuk prevent duplicate assignment. Plus pivot data: assigned_by admin, assigned_at timestamp.
>
> **Tabel 5: pegawai_internal**
> User role pegawai untuk download file asli. Email domain harus @esdm.go.id, is_approved status, verification_token untuk email verification.
>
> **Relasi Overview**:
>
> - Admin (1) → (∞) DataSurvei (one admin upload banyak survei)
> - Admin (1) → (∞) GridKotak assignment (admin assign survei ke grid)
> - DataSurvei (∞) ← → (∞) GridKotak (many-to-many via pivot)
> - PegawaiInternal download DataSurvei (one-to-many)

**Penilaian Asesor**:

- ✅ Tahu semua 5 tabel
- ✅ Mengerti fungsi & relasi
- ✅ Bisa jelaskan pivot table many-to-many
- ✅ Mengerti design decision

---

### Question 1.2 (Sedang)

**Asesor**: "Kenapa Anda pilih relasi Many-to-Many untuk grid dan survei? Jelaskan alternatif dan kekurangannya!"

**Model Answer** (2-3 menit):

> Mari saya jelaskan mengapa many-to-many adalah pilihan terbaik untuk use case ini.
>
> **Skenario Real**: Satu survei seismik biasanya panjang - panjang lintasan survei bisa spanning beberapa area geografis. Jadi satu survei bisa berada di banyak grid kotak. Sebaliknya, satu grid area bisa mengandung data survei dari berbagai tahun (overlap/tumpang tindih).
>
> **Karena relasi ini banyak ke banyak, saya gunakan Many-to-Many** dengan tabel pivot grid_seismik. Tabel pivot ini menyimpan dua foreign key: grid_kotak_id dan data_survei_id.
>
> **Alternatif 1: One-to-Many**
> Misal: grid punya banyak survei (hasMany). Tapi survei tidak bisa di banyak grid → Kurang flexible! Harus duplicate data survei di setiap grid.
>
> **Alternatif 2: Denormalization**
> Simpan grid_ids sebagai JSON array di dalam data_survei column. Contoh: grid_ids = \"[1219, 1220, 1320]\".
> Kekurangan:
>
> - Query kompleks (harus parse JSON)
> - Search by grid ID tidak efficient (database tidak bisa index JSON array)
> - Inconsistent data (kalau update grid tapi lupa update survei)
> - Scale up problem (kalau 100+ grid per survei, array jadi besar)
>
> **Alternatif 3: Document Database (MongoDB)**
> Store sebagai document dengan embedded array. Tapi untuk proyek ini, MySQL sudah cukup, tidak perlu NoSQL complexity.
>
> **Keuntungan Many-to-Many yang saya pilih**:
>
> - ✅ Data integrity (relasi di database level)
> - ✅ Query efficient (join pada tabel pivot)
> - ✅ Easy to add/remove relasi (Laravel attach/detach method)
> - ✅ Audit trail (pivot table bisa store assigned_by, assigned_at)
> - ✅ Scalable (work well even dengan 10,000 survei & 5,000 grid)
>
> **Implementasi LaravelEloquent**:
>
> ```php
> public function gridKotak(): BelongsToMany {
>     return $this->belongsToMany(
>         GridKotak::class,
>         'grid_seismik',          // Pivot table name
>         'data_survei_id',        // FK ke DataSurvei
>         'grid_kotak_id'          // FK ke GridKotak
>     )->withPivot(['assigned_by', 'assigned_at']);  // Include pivot data
> }
> ```
>
> Usage:
>
> ```php
> $survei = DataSurvei::find(5);
> $grids = $survei->gridKotak;  // Get all related grids
> $survei->gridKotak()->attach([1219, 1220, 1320]);  // Assign ke 3 grid
> ```

**Penilaian Asesor**:

- ✅✅ Tahu alasan pilihan
- ✅ Bisa jelasin alternatif & kekurangannya
- ✅ Tunjuk implementasi real di code
- ✅ Mengerti performance implications

---

### Question 1.3 (Sulit)

**Asesor**: \"Di controller, saya lihat Anda pakai `$gridsData->map()` untuk transform data. Jelaskan kenapa pakai map() daripada foreach loop biasa?\"

**Model Answer** (2-3 menit):

> Bagus pertanyaan! Mari saya explain.
>
> **Kode yang saya punya** (PetaController line 25-51):
>
> ```php
> $grids = $gridsData->map(function ($grid) {
>     return [
>         'id' => $grid->id,
>         'survei_list' => $grid->dataSurvei->map(function ($survei) {
>             return [
>                 'id' => $survei->id,
>                 'judul' => $survei->judul,
>                 // ... more fields
>             ];
>         })->toArray(),
>     ];
> });
> ```
>
> **Saya pakai `map()` bukan `foreach()` karena beberapa alasan:**
>
> **1. Readability**
> `map()` adalah functional programming approach - lebih deklaratif:
>
> > \"Transform tiap item menjadi format baru\"
>
> Sedangkan `foreach()` imperative:
>
> > \"Loop, di dalam loop buat variable baru, append ke array, ...\"
>
> Kode lebih clean & intent-nya jelas.
>
> **2. Immutability**
> `map()` return collection baru, tidak modify original.
>
> ```php
> // With foreach - modify original
> foreach ($gridsData as $grid) {
>     $grid->custom_format = '...';  // ✗ Mutate original object
> }
>
> // With map - return new
> $grids = $gridsData->map(function ($grid) {
>     return [...];  // ✓ Pure function, no side effect
> });
> ```
>
> **3. Method Chaining**
> Collection methods dapat di-chain:
>
> ```php
> $grids = $gridsData
>     ->map(function ($grid) { /* ... */ })
>     ->filter(function ($grid) { return $grid['total_data'] > 0; })
>     ->sortBy('id')
>     ->values();  // Reset array keys
> ```
>
> Dengan `foreach()`, tidak bisa chain, harus buat multiple loops.
>
> **4. Nested Loop Elegance**
> Kode saya ada nested map:
>
> ```php
> 'survei_list' => $grid->dataSurvei->map(function ($survei) {
>     // Inner transform
> })->toArray()
> ```
>
> Ini jauh lebih elegant daripada nested foreach.
>
> **Performance?**
> Usually same performance, kadang `foreach()` sedikit lebih cepat karena tidak ada function call overhead. Tapi untuk data size 5,000 grid × 5 survei per grid = 25,000 items, perbedaan negligible. Readability > micro-optimization.
>
> **When to use foreach?**
>
> - Simple loop tanpa transformation
> - Need to break early (map harus iterate semuanya)
> - Side effect operation (modify external variable)

**Penilaian Asesor**:

- ✅✅✅ Deep understanding collection methods
- ✅ Tahu trade-off (readability vs performance)
- ✅ Understand functional vs imperative
- ✅ Senior level thinking!

---

## BAGIAN II: UNIT 2 - MENGGUNAKAN SPESIFIKASI PROGRAM

### Question 2.1 (Mudah)

**Asesor**: "Sebutkan tech stack Anda dan jelaskan kenapa pilih masing-masing!"

**Model Answer** (2-3 menit):

> **Backend: Laravel 12 Framework & PHP 8.2**
> Laravel dipilih karena:
>
> - Inbuilt authentication & authorization (middleware, guards)
> - Eloquent ORM untuk query builder & relasi database
> - Easy middleware untuk security (CSRF, auth check, role-based)
> - Large active community & documentation
> - Blade template engine yang powerful
>
> PHP 8.2 untuk latest features: typed properties, match expression, null-safe operator (?->).
>
> **Frontend: Blade + Vanilla JavaScript + Leaflet.js**
>
> - Blade templates: Laravel built-in, no extra learning curve
> - Vanilla JS: No dependency, lightweight, performa bagus untuk peta real-time
> - Leaflet.js: Lightweight library untuk interactive map (OpenStreetMap), easy customize
>
> **Database: MySQL 8.0**
>
> - Support relasi kompleks (many-to-many dengan pivot table)
> - Indexing untuk query optimization
> - ACID properties untuk data integrity
> - Familiar & widely used
>
> **Server: Apache + Laragon**
>
> - Laragon: All-in-one development environment (PHP, MySQL, Apache)
> - Easy setup, no complex configuration
> - Local development environment sebelum production
>
> **Supporting Libraries**:
>
> - `barryvdh/laravel-dompdf`: PDF export reports
> - `phpoffice/phpspreadsheet`: Excel export
> - `intervention/image`: Image resizing

**Penilaian Asesor**:

- ✅ Tahu semua tech stack
- ✅ Ada alasan untuk setiap pilihan
- ✅ Aware dependencies & library untuk specific features

---

### Question 2.2 (Sedang)

**Asesor**: "Jelaskan requirement (kebutuhan) sistem dari user perspective! Bagaimana Anda translate requirement menjadi spesifikasi teknis?"

**Model Answer** (3-4 menit):

> **Analisis Requirement:**
>
> **User Story 1: Admin**
>
> - \"Saya (admin) perlu manage data survei seismik\"
> - \"Saya perlu upload survei dengan foto & dokumen asli\"
> - \"Saya assign survei ke area grid di peta\"
> - \"Saya export report untuk pelaporan\"
>
> **User Story 2: Pegawai Internal**
>
> - \"Saya (pegawai BBSPGL) perlu download file survei asli (secure, untuk penelitian)\"
> - \"Saya lihat katalog survei & search by kriteria\"
>
> **User Story 3: Public/General User**
>
> - \"Saya (publik) ingin see peta interaktif data survei\"
> - \"Saya ingin search & filter survei berdasarkan tahun, tipe, wilayah\"
>
> **Translate ke Spesifikasi Teknis:**
>
> **Requirement 1** → **Tech: Authentication + Authorization**
>
> - Dual-guard authentication (admin & pegawai)
> - Middleware untuk role-based access control
> - Admin dapat akses admin panel, pegawai dapat akses pegawai portal
> - Public dapat akses public peta & katalog
>
> **Requirement 2** → **Tech: File Upload + Storage**
>
> - File upload form untuk gambar & dokumen
> - Image optimization (resize thumbnail, medium size)
> - Secure storage untuk file asli (non-public disk)
> - Background job untuk proses image (prevent controller hang)
>
> **Requirement 3** → **Tech: Database Design**
>
> - Tabel data_survei dengan 15 kolom
> - Many-to-many relasi dengan grid (untuk assign survei ke multiple area)
> - Foreign key untuk audit trail (diunggah_oleh, assigned_by)
>
> **Requirement 4** → **Tech: Frontend**
>
> - Interactive peta dengan Leaflet.js (click grid → show data modal)
> - Search/filter/sort katalog dengan pagination
> - AJAX untuk real-time interaction (tidak reload page)
>
> **Requirement 5** → **Tech: Reporting**
>
> - PDF export via DomPDF (render view → PDF)
> - Excel export via PhpSpreadsheet (programmatic spreadsheet generation)
>
> **Requirement 6** → **Tech: Security**
>
> - CSRF protection di semua form
> - Input validation & HTML sanitasi (prevent XSS)
> - Query binding (prevent SQL injection)
> - Role-based middleware access control

**Penilaian Asesor**:

- ✅✅ Understand requirement gathering process
- ✅ Bisa translate user need → technical solution
- ✅ Holistic thinking (design sedari awal)

---

## BAGIAN III: UNIT 3 - MENERAPKAN PERINTAH EKSEKUSI

### Question 3.1 (Mudah)

**Asesor**: "Tunjukkan contoh percabangan (if-else) di kode Anda dan jelaskan logikanya!"

**Model Answer** (2 menit):

> [Buka VS Code, Ctrl+P search \"KatalogController\"]
>
> Lihat di line 22-44 di file `app/Http/Controllers/User/KatalogController.php`:
>
> ```php
> if ($request->filled('search')) {
>     $search = $request->search;
>     $query->where(function ($q) use ($search) {
>         $q->where('judul', 'LIKE', '%' . $search . '%')
>             ->orWhere('deskripsi', 'LIKE', '%' . $search . '%')
>             ->orWhere('tipe', 'LIKE', '%' . $search . '%')
>             ->orWhere('wilayah', 'LIKE', '%' . $search . '%')
>             ->orWhere('ketua_tim', 'LIKE', '%' . $search . '%');
>     });
> }
>
> if ($request->filled('tahun')) {
>     $query->where('tahun', $request->tahun);
> }
>
> if ($request->filled('tipe')) {
>     $query->where('tipe', $request->tipe);
> }
>
> if ($request->filled('wilayah')) {
>     $query->where('wilayah', 'LIKE', '%' . $request->wilayah . '%');
> }
> ```
>
> **Logika:**
>
> - Jika user input search keyword → tambah WHERE condition untuk search di judul, deskripsi, tipe, wilayah, ketua_tim
> - Jika user pilih tahun filter → tambah WHERE condition untuk exact match tahun
> - Jika user pilih tipe (2D/3D/HR) → tambah WHERE condition
> - Jika user input wilayah → tambah WHERE condition
>
> **Contoh use case:**
>
> - User search \"Peta\" + tahun 2023 → query menjadi: WHERE (judul LIKE '%Peta%' OR ...) AND tahun = 2023
> - User hanya tahun 2022 → query: WHERE tahun = 2022 (no search condition)
> - User tidak input apapun → query return all survei (no where condition)
>
> **Kenapa pakai if daripada switch?**
> Karena ini multiple independent conditions, bukan mutually exclusive case. Users bisa combine multiple filter.

**Penilaian Asesor**:

- ✅ Tunjuk file & line number spesifik
- ✅ Jelaskan logika & use case konkret
- ✅ Tahu kapan pakai if vs switch

---

### Question 3.2 (Sedang)

**Asesor**: "Di mana Anda pakai perulangan? Jelaskan kenapa perlu loop di sini!"

**Model Answer** (2-3 menit):

> Saya pakai perulangan di banyak tempat. Contoh paling penting:
>
> [Buka PetaController.php line 25-51]
>
> Di sini ada **nested map() loop** - loop dalam loop:
>
> ```php
> $grids = $gridsData->map(function ($grid) {  // Loop 1: setiap grid
>     return [
>         'id' => $grid->id,
>         'nomor_kotak' => $grid->nomor_kotak,
>         'total_data' => $grid->dataSurvei->count(),
>         'survei_list' => $grid->dataSurvei->map(function ($survei) {  // Loop 2: setiap survei dalam grid
>             return [
>                 'id' => $survei->id,
>                 'judul' => $survei->judul,
>                 'tahun' => $survei->tahun,
>             ];
>         })->toArray(),
>     ];
> });
> ```
>
> **Kenapa perlu loop?**
>
> - Loop 1: Iterate semua grid kotak (bisa 5,000 grid). Transform data setiap grid.
> - Loop 2 (nested): Untuk setiap grid, iterate semua survei dalam grid itu. Transform setiap survei ke format JSON untuk Leaflet.js.
>
> **Tanpa loop, apa yang terjadi?**
>
> - Data tidak terbaca - hanya ambil grid pertama aja?
> - Frontend Leaflet tidak tahu struktur data, tidak bisa render peta dengan benar.
>
> **Contoh lain**: [Buka ExportController.php line ???]
> Export ke Excel juga pakai foreach untuk loop setiap survei, write ke baris baru di spreadsheet:
>
> ```php
> foreach ($surveiData as $row => $survei) {
>     $sheet->setCellValue('A' . ($row + 15), $survei->judul);
>     $sheet->setCellValue('B' . ($row + 15), $survei->tahun);
>     // ... more cells
> }
> ```
>
> Tanpa loop, hanya 1 survei yang ter-export.

**Penilaian Asesor**:

- ✅ Tunjuk 2+ contoh loop
- ✅ Jelaskan consequence kalau tidak pakai loop
- ✅ Mengerti nested loop logic

---

### Question 3.3 (Sulit)

**Asesor**: "Tunjukkan multimedia implementation di kode Anda - upload image dan export PDF/Excel!"

**Model Answer** (3-4 menit):

> **1. IMAGE UPLOAD** [Buka DataSurveiController.php line 70-100]:
>
> ```php
> if ($request->hasFile('gambar_pratinjau')) {
>     $file = $request->file('gambar_pratinjau');
>     $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
>     $path = $file->storeAs('gambar_pratinjau', $fileName, 'public');
>     $data['gambar_pratinjau'] = $path;
> }
> ```
>
> **Logika:**
>
> - Validasi: check kalau file ada (`hasFile()`)
> - Get file object dari request
> - Generate random filename untuk security (prevent guessing filename)
> - Store ke disk 'public' folder 'gambar_pratinjau'
> - Save path ke database
>
> **Background Job untuk resize**:
>
> ```php
> ProcessSurveiImage::dispatch($survei->id)->delay(now()->addSeconds(5));
> ```
>
> Tidak langsung resize di controller (bisa hang). Queue di background job. User tidak perlu tunggu.
>
> **2. PDF EXPORT** [Buka ExportController.php line ???]:
>
> ```php
> $pdf = Pdf::loadView('admin.laporan.pdf', compact('dataSurvei', 'stats'));
> return $pdf->download('laporan_survei_' . date('YmdHis') . '.pdf');
> ```
>
> **Logika:**
>
> - Render Blade view template jadi PDF
> - Pass data $dataSurvei & $stats ke view
> - Return untuk download dengan nama file berisi timestamp
>
> Di view `admin.laporan.pdf`, saya loop survei:
>
> ```blade
> @foreach($dataSurvei as $survei)
>     <tr>
>         <td>{{ $survei->judul }}</td>
>         <td>{{ $survei->tahun }}</td>
>     </tr>
> @endforeach
> ```
>
> **3. EXCEL EXPORT** [Buka ExportController.php line 50-100]:
>
> ```php
> $spreadsheet = new Spreadsheet();
> $sheet = $spreadsheet->getActiveSheet();
>
> // Set header cell
> $sheet->setCellValue('A1', 'Judul');
> $sheet->setCellValue('B1', 'Tahun');
>
> // Loop survei, set data cells
> foreach ($surveiData as $row => $survei) {
>     $sheet->setCellValue('A' . ($row + 2), $survei->judul);
>     $sheet->setCellValue('B' . ($row + 2), $survei->tahun);
> }
>
> // Style header (bold, background)
> $sheet->getStyle('A1:B1')->applyFromArray([
>     'font' => ['bold' => true],
>     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']]
> ]);
>
> // Download
> $writer = new Xlsx($spreadsheet);
> return response()->stream(
>     fn() => $writer->save('php://output'),
>     200,
>     ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
> );
> ```
>
> **Logika:**
>
> - Buat workbook baru (Spreadsheet object)
> - Set header row (A1, B1, ...)
> - Loop survei, set setiap cell dengan data
> - Apply styling (bold header, background color)
> - Stream response sebagai Excel file
>
> **Keuntungan stream() daripada save ke disk:**
>
> - Tidak perlu create temporary file
> - User langsung download tanpa intermediate file
> - Memory efficient untuk large exports

**Penilaian Asesor**:

- ✅✅✅ Comprehensive multimedia handling
- ✅ Tunjuk 3 berbeda multimedia implementation
- ✅ Mengerti file handling, streaming, memory management

---

## BAGIAN IV: UNIT 7 & 8 (DEBUGGING & TESTING)

### Question 7.1 (Mudah)

**Asesor**: "Saat development, ada error saat jalankan aplikasi. Bagaimana Anda debug?"

**Model Answer** (2 menit):

> Prosedur debugging saya:
>
> **Step 1: Read Error Message**
> Laravel error page sudah informatif - tunjuk file & line number. Saya baca message dengan teliti.
>
> **Step 2: Check Log File**
>
> ```bash
> tail -f storage/logs/laravel-xxxx.log
> ```
>
> Lihat ada exception trace lebih detail?
>
> **Step 3: Add Debug Code**
>
> ```php
> dd($variable);      // Dump & Die - lihat isi variable stop
> Log::info('Debug:', $variable);
> ```
>
> **Step 4: Check Frontend**
> Browser F12 → Network tab lihat request/response
> Console tab lihat JS error
>
> **Step 5: Database Query**
> Laravel Debugbar (bawaan) show semua SQL query yang dijalankan
> Atau direct MySQL: `SELECT ...` untuk verify data ada
>
> **Step 6: Fix & Test**
> Implement fix. Clear cache: `php artisan cache:clear`
> Refresh browser: Ctrl+Shift+Del (hard refresh)
> Test lagi sampai fixed

**Penilaian Asesor**:

- ✅ Systematic approach
- ✅ Know tools (log, debugbar, devtools)

---

### Question 8.1 (Sedang)

**Asesor**: "Tunjukkan test case black box untuk sistem Anda!"

**Model Answer** (2-3 menit):

> **Black Box Testing = Test dari user perspective, tanpa lihat code**
>
> Mari saya prepare beberapa test cases:
>
> | Test Case           | Input                               | Expected Output                                        | Status |
> | ------------------- | ----------------------------------- | ------------------------------------------------------ | ------ |
> | Search survei       | Buka katalog, search \"peta\"       | Hanya survei dengan \"peta\" di judul/deskripsi tampil | PASS   |
> | Filter tahun        | Pilih tahun 2023 di dropdown        | Hanya survei tahun 2023 tampil                         | PASS   |
> | Sort terbaru        | Pilih sort \"Terbaru\"              | Survei diurutkan by created_at descending              | PASS   |
> | Paging              | Klik halaman 2 katalog              | Survei berbeda dari halaman 1                          | PASS   |
> | Click grid peta     | Buka peta, click grid 1219          | Modal popup show survei dalam grid 1219                | PASS   |
> | Download file       | Admin upload file, pegawai download | File ter-download dengan benar                         | PASS   |
> | Export Excel        | Admin klik export Excel             | File.xlsx ter-download                                 | PASS   |
> | Login invalid       | Login dengan password salah         | Error message muncul                                   | PASS   |
> | Upload besar        | Upload image > 5MB                  | Error message \"File terlalu besar\"                   | PASS   |
> | Unauthorized access | Public akses /admin route           | Redirect ke login atau 403 Forbidden                   | PASS   |
>
> **Execution**: Saya buka aplikasi, jalankan setiap test case, verifikasi output sesuai ekspektasi.
>
> **Kalau ada yang FAIL**: Saya catat, debug, report ke developer team.

**Penilaian Asesor**:

- ✅ Tahu prinsip black box
- ✅ Siap test cases konkret
- ✅ Menggunakan table format systematic

---

### Question 8.2 (Sulit)

**Asesor**: "Bagaimana implementasi white box testing dengan PHPUnit untuk sistem Anda?"

**Model Answer** (3-4 menit):

> **White Box Testing = Test internal logic, knowing code structure**
>
> Saya buat test class dengan PHPUnit untuk test function-specific logic.
>
> **Example 1: Test Search Filter Function**
>
> ```php
> // tests/Feature/KatalogControllerTest.php
> public function test_search_survei_by_title() {
>     // Arrange: Setup data
>     $survei1 = DataSurvei::create(['judul' => 'Survei Peta', 'tahun' => 2023]);
>     $survei2 = DataSurvei::create(['judul' => 'Survei Batuan', 'tahun' => 2023]);
>
>     // Act: Call function
>     $response = $this->get('/katalog?search=Peta');
>
>     // Assert: Verify
>     $response->assertStatus(200);
>     $response->assertSee('Survei Peta');
>     $response->assertDontSee('Survei Batuan');
> }
> ```
>
> **Apa ditest?**
>
> - HTTP status code 200 (request berhasil)
> - Response HTML contains \"Survei Peta\" (correct data)
> - Response NOT contains \"Survei Batuan\" (no unrelated data)
>
> **Example 2: Test Validation**
>
> ```php
> public function test_upload_survei_invalid_tipe() {
>     $response = $this->post('/admin/survei/store', [
>         'judul' => 'Test',
>         'tahun' => 2023,
>         'tipe' => 'INVALID',  // ✗ Harus 2D/3D/HR
>         'wilayah' => 'Laut'
>     ]);
>
>     // Expect validation error
>     $response->assertSessionHasErrors('tipe');
> }
> ```
>
> **Apa ditest?**
>
> - Input validation works
> - Invalid tipe rejected
> - Error message di session
>
> **Example 3: Test Database Relasi (Many-to-Many)**
>
> ```php
> public function test_survei_linked_to_grid() {
>     $grid = GridKotak::create([/* ... */]);
>     $survei = DataSurvei::create([/* ... */]);
>
>     // Assign
>     $survei->gridKotak()->attach($grid->id);
>
>     // Verify database
>     $this->assertDatabaseHas('grid_seismik', [
>         'grid_kotak_id' => $grid->id,
>         'data_survei_id' => $survei->id
>     ]);
>
>     // Verify relation works
>     $result = $survei->gridKotak()->where('id', $grid->id)->exists();
>     $this->assertTrue($result);
> }
> ```
>
> **Apa ditest?**
>
> - Attach method works
> - Pivot table data inserted
> - Dapat query relasi kembali
>
> **Running Tests**:
>
> ```bash
> php artisan test                          # Run all tests
> php artisan test tests/Feature/...        # Run specific file
> php artisan test --filter test_search     # Run by name
> php artisan test --coverage               # Check code coverage %
> ```
>
> **Coverage Target**: ≥ 80% artinya semua major code path sudah covered oleh test.

**Penilaian Asesor**:

- ✅✅✅ Advanced testing knowledge
- ✅ Tunjuk 3 jenis white box test
- ✅ Mengerti AAA pattern (Arrange-Act-Assert)
- ✅ Tahu coverage concept

---

## BEHAVIORAL QUESTIONS

### B.1 (Problem-Solving)

**Asesor**: "Bayangkan user report: 'Grid peta tidak muncul, tapi error message tidak ada'. Root cause investigation?"

**Model Answer** (2-3 menit):

> Ooh, yang tanpa error message itu tricky. Ini scenario silent failure. Mari saya investigasi step-by-step:
>
> **Step 1: Reproduce Issue**
> Saya buka aplikasi, navigate ke /peta, lihat apakah emang grid tidak render.
>
> **Step 2: Check Browser Console (F12)**
> Buka DevTools → Console tab. Ada JS error? Atau network error?
>
> **Step 3: Check Network Request**
> Network tab. Request ke `/api/peta` atau endpoint yang mana? Status? 200 atau 404 atau 500?
> Lihat response body - ada data atau kosong/error?
>
> **Hypothetical Path 1: Status 200 tapi response kosong**
> → Backend return data gak balanced
> → Check controller: apakah query bener? apakah data ada di DB?
> → Verify: `SELECT * FROM grid_kotak` di MySQL. Ada data?
> → Kalau ada data, mungkin query filtered dengan condition yang wrong
>
> **Hypothetical Path 2: Status 500 error tapi JavaScript not catch**
> → Backend error tapi frontend tidak handle promise rejection
> → Check JS code: apakah ada .catch() untuk AJAX error?
> → Add error handler:
>
> ```javascript
> fetch("/api/peta")
>     .then((r) => r.json())
>     .then((data) => renderMap(data))
>     .catch((err) => console.error("Error:", err)); // ← Ini yg ketinggalan!
> ```
>
> **Hypothetical Path 3: Status 0 network error**
> → CORS issue? Network timeout?
> → Check browser Network tab headers
> → Check server accessibility: `ping server`
>
> **Fix Strategy**:
>
> 1. Identify root cause dari steps atas
> 2. Add error logging/monitoring (Sentry, New Relic)
> 3. Add user-friendly error message di UI
> 4. Test lagi
> 5. Document learning untuk prevent future
>
> **Lesson**: Always provide error handling. Silent failure adalah worst UX. User tidak tahu apa salah, developer tidak tahu apa broke.

**Penilaian Asesor**:

- ✅✅ Systematic debugging
- ✅ Consider multiple hypotheses
- ✅ Aware UX implications

---

### B.2 (Learning Agility)

**Asesor**: "Anda belum pernah pakai Leaflet.js sebelum project ini. Bagaimana Anda belajar?"

**Model Answer** (2 menit):

> Great question! Proses learning saya:
>
> **1. Official Documentation**
> Pertama saya baca official Leaflet.js docs di leafletjs.com. Cek tutorial, example dari maintainer sendiri.
>
> **2. Tutorial & Video**
> Search YouTube \"Leaflet.js interactive map tutorial\". Tonton 2-3 tutorial untuk understand basic konsep (canvas, markers, polygons, popups).
>
> **3. Trial & Error di Dev Environment**
> Buat file test.html, pake Leaflet, experiment. Coba buat basic peta, coba add marker, coba add circle/polygon.
>
> **4. Adapt ke Project**
> Setelah basic paham, saya adapt ke project saya. Transform grid_kotak data (bounding box coordinates) menjadi Leaflet polygon rectangle. Add click event listener. Handle popup modal.
>
> **5. Debugging** (when stuck)
> Browser console (F12) untuk debug. Check kalau data structure correct. Test dengan hardcoded data dulu, sebelum dynamic dari API.
>
> **6. Ask Help**
> Kalau stuck 1-2 jam, saya tanya teman/stackoverflow, atau ask ChatGPT \"How to...\"
>
> **7. Document Learnings**
> Saya catat di note: apa yang saya pelajari, gotcha yang saya temui, cara yang bekerja. Buat reference untuk project berikutnya.
>
> **Tidak langsung copy-paste dari StackOverflow** - saya understand kode before implement.

**Penilaian Asesor**:

- ✅ Self-learning initiative
- ✅ Resourceful
- ✅ Not afraid of new technologies

---

## SUMMARY: CHECKLIST SEBELUM UJIKOM

- [ ] Hafal 5 tabel & relasi database
- [ ] Bisa tunjuk 3+ contoh percabangan di KatalogController
- [ ] Bisa tunjuk nested loop di PetaController
- [ ] Tunjuk file image upload, PDF export, Excel export
- [ ] Siap jawab kenapa Many-to-Many vs alternatif lain
- [ ] Siap jawab tech stack & alasan pilihan
- [ ] Siap explain debugging process step-by-step
- [ ] Siap mock interview dengan 5+ model answers di atas

Good luck! 🚀
