# 📊 Dokumentasi Diagram Sistem
## Data Peta Seismik

### 📁 Daftar Dokumen

| No | Dokumen | Deskripsi | Link |
|----|---------|-----------|------|
| 1 | **ERD.md** | Entity Relationship Diagram - Struktur database dan relasi antar tabel | [Lihat ERD](./ERD.md) |
| 2 | **USE_CASE.md** | Use Case Diagram - Interaksi aktor dengan sistem | [Lihat Use Case](./USE_CASE.md) |
| 3 | **CLASS_DIAGRAM.md** | Class Diagram - Struktur kelas (Entity, Control, Boundary) | [Lihat Class Diagram](./CLASS_DIAGRAM.md) |

---

## 📋 Ringkasan Sistem

### Tentang Aplikasi
**Data Peta Seismik** adalah sistem informasi berbasis web untuk mengelola dan memetakan data survei seismik di Indonesia. Sistem ini dikembangkan menggunakan framework Laravel (PHP) dengan arsitektur MVC.

### Fitur Utama
1. **Peta Interaktif** - Menampilkan grid kotak peta Indonesia dengan data survei
2. **Katalog Data** - Pencarian dan filter data survei seismik
3. **Manajemen Data** - CRUD data survei oleh admin
4. **Sistem Grid** - Pemetaan data survei ke lokasi grid peta
5. **Akses Pegawai** - Download file scan asli khusus pegawai internal
6. **Laporan & Export** - Generate laporan dalam format PDF/Excel

### Aktor Sistem
1. **User Publik** - Pengunjung website tanpa login
2. **Pegawai Internal** - Pegawai ESDM dengan email @esdm.go.id
3. **Admin** - Administrator pengelola sistem

---

## 🛠️ Cara Melihat Diagram

### Option 1: Mermaid Preview (Recommended)
Semua diagram ditulis dalam format **Mermaid**. Anda dapat melihatnya dengan:
- **VS Code**: Install extension "Markdown Preview Mermaid Support"
- **GitHub/GitLab**: Otomatis render diagram Mermaid
- **Online**: Copy kode ke [Mermaid Live Editor](https://mermaid.live/)

### Option 2: Export ke Gambar
1. Buka [Mermaid Live Editor](https://mermaid.live/)
2. Copy kode diagram dari file `.md`
3. Klik "Export" untuk download sebagai PNG/SVG

---

## 📈 Statistik Sistem

| Komponen | Jumlah |
|----------|--------|
| Tabel Database | 5 |
| Entity (Model) | 5 |
| Controller | 17 |
| View | 12+ |
| Use Case | 33 |
| Aktor | 3 |

---

## 📝 Catatan

- Dokumen ini dibuat untuk keperluan dokumentasi PKL
- Diagram menggunakan format Mermaid untuk kemudahan maintenance
- Semua diagram sesuai dengan kode aktual di proyek ini

**Last Updated**: Januari 2026

