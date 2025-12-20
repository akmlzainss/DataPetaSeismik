# Product Specification Document (PRD)
# Data Peta Seismik - Seismic Data Map System

**Project Name:** Data Peta Seismik  
**Version:** 1.0.0  
**Framework:** Laravel 12.x  
**PHP Version:** 8.2+  
**Type:** Full-Stack Web Application  
**Date:** December 16, 2025  
**Author:** System Analyst

---

## 1. Executive Summary

**Data Peta Seismik** adalah sistem manajemen data seismik berbasis web yang memungkinkan pengguna untuk mengelola, memvisualisasikan, dan mengakses data survei seismik melalui peta interaktif. Sistem ini memiliki dua interface utama: panel admin untuk manajemen data dan portal publik untuk akses informasi seismik.

### 1.1 Key Objectives
- Menyediakan platform terpusat untuk pengelolaan data survei seismik
- Visualisasi data seismik melalui peta interaktif dengan marker georeferensi
- Menyediakan akses publik untuk informasi seismik
- Generate laporan dan export data dalam berbagai format (PDF, Excel)
- Manajemen user dengan role-based access control

---

## 2. System Architecture

### 2.1 Technology Stack

**Backend:**
- Framework: Laravel 12.0
- Language: PHP 8.2
- Database: MySQL/MariaDB (via Laragon)
- ORM: Eloquent

**Frontend:**
- Template Engine: Blade
- JavaScript: Vanilla JS + Leaflet.js/Mapbox (untuk peta)
- CSS: Custom CSS
- Build Tool: Vite

**Third-Party Libraries:**
- barryvdh/laravel-dompdf: PDF generation
- phpoffice/phpspreadsheet: Excel export/import
- intervention/image: Image processing
- ezyang/htmlpurifier: HTML sanitization

**Testing:**
- PHPUnit 11.5.3
- Laravel Dusk (potensial untuk browser testing)

**Development Environment:**
- Laragon (local server)
- Composer: Dependency management
- NPM: Frontend asset management

### 2.2 Application Structure

```
DataPetaSeismik/
├── app/
│   ├── Console/         # Artisan commands
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/   # Admin panel controllers
│   │   │   └── User/    # Public-facing controllers
│   │   └── Middleware/
│   ├── Models/          # Eloquent models
│   │   ├── Admin.php
│   │   ├── DataSurvei.php
│   │   ├── LokasiMarker.php
│   │   └── User.php
│   ├── Services/        # Business logic layer
│   └── Mail/            # Email templates
├── database/
│   ├── migrations/      # Database schema
│   ├── seeders/         # Test data
│   └── factories/       # Model factories
├── resources/
│   └── views/
│       ├── admin/       # Admin templates
│       └── user/        # Public templates
├── routes/
│   ├── web.php          # Web routes
│   └── api.php          # API routes (if any)
├── public/              # Public assets
├── tests/               # Unit & Feature tests
└── testsprite_tests/    # TestSprite generated tests
```

---

## 3. Core Features & Functionality

### 3.1 User Management Module

**Models:** `User`, `Admin`

**Features:**
- User registration and authentication
- Admin authentication (separate login)
- Password reset functionality
- Role-based access control (Admin, Public User)
- Profile management

**Controllers:**
- `Admin\AdminAuthController`: Admin authentication
- `Admin\ForgotPasswordController`: Password recovery

**Test Coverage Priority:** HIGH
- Authentication flows (login, logout, session management)
- Password reset functionality
- Authorization checks
- Input validation

---

### 3.2 Data Survei (Survey Data) Module

**Model:** `DataSurvei`

**Features:**
- CRUD operations untuk data survei seismik
- Upload dan manajemen file/dokumen survei
- Asosiasi data survei dengan lokasi marker
- Validasi data survei
- Pencarian dan filtering data
- Detail view dengan informasi lengkap

**Controllers:**
- `Admin\DataSurveiController`: Admin CRUD operations

**Data Fields (typical):**
- ID Survei
- Nama Survei
- Tanggal Survei
- Lokasi (koordinat geografis)
- Jenis Survei
- Parameter Seismik
- Status
- File/Dokumen terkait
- Metadata (created_at, updated_at)

**Test Coverage Priority:** CRITICAL
- Create, Read, Update, Delete operations
- File upload functionality
- Data validation
- Search and filtering
- Association with markers
- Permission checks

---

### 3.3 Lokasi Marker (Map Markers) Module

**Model:** `LokasiMarker`

**Features:**
- CRUD operations untuk marker peta
- Geolocation (latitude, longitude)
- Custom marker icons/styling
- Popup information untuk setiap marker
- Association dengan data survei
- Map clustering untuk multiple markers

**Controllers:**
- `Admin\LokasiMarkerController`: Marker management
- `User\PetaController`: Public map display

**Data Fields (typical):**
- ID Marker
- Nama Lokasi
- Latitude
- Longitude
- Deskripsi
- Icon/Kategori
- Data Survei terkait
- Status (aktif/non-aktif)

**Test Coverage Priority:** CRITICAL
- Marker CRUD operations
- Geolocation validation
- Map rendering
- Popup display consistency
- Marker-survey association
- Public vs admin map views

---

### 3.4 Katalog (Catalog) Module

**Features:**
- Public catalog untuk browsing data seismik
- Filtering berdasarkan kategori, tanggal, lokasi
- Search functionality
- Pagination
- Preview dan detail view

**Controllers:**
- `User\KatalogController`

**Test Coverage Priority:** HIGH
- Catalog listing
- Search and filters
- Pagination
- Detail view
- Public access (no authentication required)

---

### 3.5 Peta Interaktif (Interactive Map)

**Features:**
- Leaflet/Mapbox integration
- Marker display dengan informasi popup
- Map controls (zoom, pan, layers)
- Marker clustering
- Custom styling untuk popup
- Responsive design

**Technical Implementation:**
- JavaScript-based map library
- AJAX untuk dynamic marker loading
- Consistent popup styling
- Loading overlays

**Test Coverage Priority:** CRITICAL
- Map initialization
- Marker rendering
- Popup functionality
- Loading states
- Mobile responsiveness
- Cross-browser compatibility

---

### 3.6 Export & Reporting Module

**Features:**
- Export data ke PDF
- Export data ke Excel (XLSX)
- Custom report templates
- Filter data sebelum export
- Scheduled reports (potensial)

**Controllers:**
- `Admin\ExportController`
- `Admin\LaporanController`

**Libraries:**
- DomPDF untuk PDF generation
- PhpSpreadsheet untuk Excel

**Test Coverage Priority:** HIGH
- PDF generation
- Excel export
- Data accuracy in exports
- Template rendering
- Large dataset handling

---

### 3.7 Dashboard & Analytics

**Features:**
- Admin dashboard dengan statistik
- Chart/graph untuk visualisasi data
- Quick actions
- System notifications
- Activity logs

**Controllers:**
- `Admin\DashboardController`

**Test Coverage Priority:** MEDIUM
- Statistics calculation
- Chart data accuracy
- Performance dengan large datasets

---

### 3.8 Information Pages

**Features:**
- Halaman Home
- Tentang Kami (About Us)
- Kontak
- Info/FAQ

**Controllers:**
- `User\HomeController`
- `User\TentangKamiController`
- `User\KontakController`
- `User\InfoController`

**Test Coverage Priority:** LOW-MEDIUM
- Page rendering
- Contact form submission
- Content display

---

### 3.9 Settings & Configuration

**Features:**
- System settings management
- Configuration parameters
- Email settings
- Maintenance mode

**Controllers:**
- `Admin\PengaturanController`

**Test Coverage Priority:** MEDIUM
- Settings CRUD
- Configuration validation
- Permission checks

---

## 4. Database Schema Overview

### 4.1 Core Tables

**users**
- id
- name
- email
- password
- email_verified_at
- remember_token
- role
- timestamps

**admins**
- id
- name
- email
- password
- remember_token
- timestamps

**data_survei**
- id
- nama_survei
- tanggal_survei
- lokasi_marker_id
- jenis_survei
- deskripsi
- file_path
- status
- created_by
- timestamps

**lokasi_markers**
- id
- nama_lokasi
- latitude
- longitude
- deskripsi
- icon_type
- status
- timestamps

### 4.2 Relationships
- `DataSurvei` belongsTo `LokasiMarker`
- `LokasiMarker` hasMany `DataSurvei`
- `DataSurvei` belongsTo `Admin` (created_by)

---

## 5. Testing Strategy for TestSprite

### 5.1 Frontend Testing

**Type:** `frontend`  
**Port:** `8000` (php artisan serve)  
**Main Page:** `/` (public home), `/admin` (admin panel)

**Test Scenarios:**

1. **Public Map Testing** (`/peta`)
   - Map loads correctly
   - Markers render pada posisi yang benar
   - Popup displays dengan format konsisten
   - Map controls berfungsi (zoom, pan)
   - Loading overlay muncul dan hilang dengan benar
   - Responsive pada berbagai screen sizes

2. **Admin Map Testing** (`/admin/data-survei/{id}`)
   - Map rendering pada detail page
   - Loading overlay tidak stuck/selalu visible
   - Popup styling konsisten dengan public map
   - CRUD operations pada markers

3. **Catalog Page** (`/katalog`)
   - Grid/list view rendering
   - Search functionality
   - Filters working
   - Pagination
   - Card/item click navigation

4. **Admin Dashboard** (`/admin/dashboard`)
   - Statistics display
   - Charts rendering
   - Quick action buttons
   - Notifications display

5. **Form Testing**
   - Data Survei create/edit forms
   - Lokasi Marker forms
   - File upload functionality
   - Validation messages
   - Success/error feedback

6. **Authentication Flows**
   - Admin login page
   - User login (if applicable)
   - Logout functionality
   - Password reset

7. **Popup Standardization** (CRITICAL)
   - Consistent styling across:
     - Admin catalog popup
     - Public map marker popup
     - Admin survey detail popup
   - Button alignment dan sizing
   - Text centering dan readability
   - Font sizes dan styles
   - Color schemes

### 5.2 Backend Testing

**Type:** `backend`  
**Framework:** Laravel PHPUnit

**Test Scenarios:**

1. **API/Route Testing**
   - All routes return correct status codes
   - Middleware protection working
   - CORS handling (if API exists)

2. **Model Testing**
   - Model relationships
   - Accessors/Mutators
   - Scopes
   - Validation rules

3. **Controller Testing**
   - All CRUD operations
   - Authorization checks
   - Input validation
   - Response formats

4. **Service Layer Testing**
   - Business logic correctness
   - Data processing
   - Integration dengan external services

5. **Database Testing**
   - Migrations dapat run dan rollback
   - Seeders berfungsi
   - Foreign key constraints
   - Index performance

6. **File Upload Testing**
   - File storage
   - Validation (type, size)
   - File deletion
   - Path resolution

7. **Export Functionality**
   - PDF generation accuracy
   - Excel export data integrity
   - Template rendering
   - Performance dengan large datasets

8. **Email Functionality**
   - Email dapat dikirim
   - Template rendering correctly
   - Queue processing (if used)

9. **Authentication & Authorization**
   - Login/logout
   - Password hashing
   - Session management
   - Permission checks
   - Guard separation (admin vs user)

---

## 6. Known Issues & Fixes

### 6.1 Current Issues (from conversation history)

**Issue 1: Loading Overlay Always Visible on Admin Survey Detail**
- Location: Admin data survei show page
- Problem: Loading overlay tidak hilang setelah map loaded
- Priority: HIGH
- Fix: Ensure JavaScript properly hides loading overlay after map initialization

**Issue 2: Inconsistent Popup Styling**
- Location: Map popups across different pages
- Problem: Different styling antara admin catalog, public map, dan admin survey detail
- Priority: MEDIUM-HIGH
- Required: Standardize CSS classes, button sizes, text alignment

**Issue 3: Button Alignment in Popups**
- Problem: Buttons tidak centered dengan benar
- Required: CSS flexbox/grid untuk proper centering

---

## 7. TestSprite Configuration

### 7.1 Bootstrap Configuration

```
Project Path: c:\laragon\www\DataPetaSeismik
Local Port: 8000
Type: frontend & backend
Test Scope: codebase
```

### 7.2 Frontend Test Configuration

```
needLogin: true (untuk admin pages)
needLogin: false (untuk public pages)
pathname: 
  - / (home)
  - /peta (public map)
  - /katalog (catalog)
  - /admin (admin login)
  - /admin/dashboard
  - /admin/data-survei
  - /admin/lokasi-marker
```

### 7.3 Test Plan Generation

**Frontend Test Plan:**
- Generate test plan dengan `needLogin: true` untuk admin flows
- Generate separate test plan dengan `needLogin: false` untuk public flows
- Focus area: Map rendering, popup consistency, form validation, navigation

**Backend Test Plan:**
- Focus pada CRUD operations
- Database integrity
- File upload/download
- Export functionality
- Authentication/Authorization

### 7.4 Test Execution Priority

**Phase 1: Critical Path (Run First)**
1. Authentication flows
2. Map rendering & markers
3. Popup standardization
4. Data Survei CRUD

**Phase 2: Core Features**
5. Lokasi Marker CRUD
6. Catalog functionality
7. Search & filtering
8. Export functionality

**Phase 3: Supporting Features**
9. Dashboard & analytics
10. Information pages
11. Settings management
12. Email functionality

---

## 8. Success Criteria

### 8.1 Functional Requirements
- ✅ Semua CRUD operations berfungsi tanpa error
- ✅ Map rendering correctly dengan semua markers
- ✅ Popups consistent styling di semua pages
- ✅ File upload/download working
- ✅ Export PDF & Excel producing correct data
- ✅ Authentication & authorization properly enforced
- ✅ Search & filtering returning correct results

### 8.2 Performance Requirements
- Page load time < 3 seconds
- Map initialization < 2 seconds
- Export generation < 5 seconds untuk dataset normal
- Zero JavaScript console errors
- Zero PHP errors/warnings

### 8.3 UI/UX Requirements
- Responsive design (mobile, tablet, desktop)
- Consistent styling across all pages
- Loading states properly displayed
- Error messages user-friendly
- Success feedback clear

### 8.4 Code Quality
- PSR-12 coding standards compliance
- No security vulnerabilities
- Proper error handling
- Commented complex logic
- DRY principle applied

---

## 9. Test Execution Commands

### 9.1 Start Local Server
```bash
php artisan serve
# Server akan run di http://localhost:8000
```

### 9.2 Run Backend Tests (PHPUnit)
```bash
composer test
# atau
php artisan test
```

### 9.3 Run TestSprite Tests
```bash
# Bootstrap akan di-run oleh TestSprite MCP
# Generate frontend test plan
# Generate backend test plan
# Execute tests
# Analyze results
```

---

## 10. Dependencies & Environment

### 10.1 Required Environment Variables
```
APP_NAME="Data Peta Seismik"
APP_ENV=local
APP_KEY=[generated]
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datapetaseismik
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### 10.2 Setup Commands
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

---

## 11. Additional Notes

### 11.1 Code Standards
- Follow Laravel best practices
- Use Eloquent ORM for database operations
- Service layer untuk complex business logic
- FormRequest untuk validation
- Resource controllers untuk CRUD

### 11.2 Security Considerations
- CSRF protection pada semua forms
- SQL injection prevention (Eloquent)
- XSS prevention (Blade escaping, HTMLPurifier)
- Authentication guards properly configured
- File upload validation strict
- Authorization middleware pada admin routes

### 11.3 Maintenance
- Regular dependency updates
- Database backups
- Log rotation
- Cache clearing strategy
- Session cleanup

---

## 12. TestSprite Integration Checklist

- [ ] Project path configured correctly
- [ ] Local server running pada port 8000
- [ ] Database seeded dengan test data
- [ ] Frontend test plan generated
- [ ] Backend test plan generated
- [ ] Admin login credentials available
- [ ] All environment variables set
- [ ] NPM dependencies installed
- [ ] Composer dependencies installed
- [ ] Storage permissions correct
- [ ] Map API keys configured (if needed)

---

## 13. Contact & Support

**Development Team:**
- Developer: [Your Name]
- Project Manager: [PM Name]
- QA Lead: [QA Name]

**Documentation:**
- Laravel Docs: https://laravel.com/docs
- TestSprite Guide: [TestSprite documentation]

**Repository:**
- GitHub: akmlzainss/DataPetaSeismik

---

**Document Version:** 1.0.0  
**Last Updated:** December 16, 2025  
**Status:** Ready for TestSprite Configuration
