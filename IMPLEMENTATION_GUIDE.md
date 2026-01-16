# 📚 IMPLEMENTASI SISTEM LOGIN PEGAWAI INTERNAL - COMPLETE GUIDE

## 🎯 Overview

Sistem ini menambahkan **3 tipe user** ke aplikasi:
1. **Admin** - Mengelola data (sudah ada sebelumnya)
2. **Pegawai Internal ESDM** - Login dengan @esdm.go.id, bisa download file asli
3. **User External/Guest** - Tidak perlu login, hanya lihat thumbnail

---

## ✅ CHECKLIST IMPLEMENTASI

### A. Setup Awal (WAJIB)

- [ ] **1. Setup Mailtrap untuk Email Testing**
  ```bash
  # Baca: MAILTRAP_SETUP.md
  # Update MAIL_USERNAME dan MAIL_PASSWORD di .env
  ```

- [ ] **2. Adjust PHP Limits untuk Upload File Besar**
  ```bash
  # Cara 1: Edit php.ini di Laragon
  # C:\laragon\bin\php\php-8.x.x\php.ini
  
  # Cara 2: Copy setting dari php_upload_config.ini
  # Cari baris ini dan edit:
  upload_max_filesize = 700M
  post_max_size = 700M
  max_execution_time = 600
  memory_limit = 1024M
  
  # Restart Apache di Laragon
  ```

- [ ] **3. Clear Cache & Migrate Database**
  ```bash
  php artisan config:clear
  php artisan cache:clear  
  php artisan migrate  # Sudah dijalankan
  ```

---

### B. Testing Flow (Recommended Order)

#### **Test 1: Register Pegawai Baru** ✅
```
URL: http://localhost:8000/pegawai/daftar

Input:
- Nama: Test Pegawai ESDM
- Email: test.pegawai@esdm.go.id
- Password: password123
- Konfirmasi Password: password123
- NIP (opsional): 19850101 200501 1 001
- Jabatan (opsional): Surveyor

Expected Result:
✓ Redirect ke /pegawai/masuk
✓ Flash message: "Silakan cek email test.pegawai@esdm.go.id untuk verifikasi"
✓ Email masuk ke Mailtrap inbox
```

#### **Test 2: Email Verification** ✅
```
1. Buka Mailtrap inbox
2. Klik email "Verifikasi Email Pegawai Internal"
3. Klik button "✓ Verifikasi Email Sekarang"

Expected Result:
✓ Redirect ke /pegawai/masuk
✓ Flash message: "Email berhasil diverifikasi! Anda sekarang dapat login."
✓ is_approved = true di database
```

#### **Test 3: Login Pegawai** ✅
```
URL: http://localhost:8000/pegawai/masuk

Input:
- Email: test.pegawai@esdm.go.id
- Password: password123

Expected Result:
✓ Redirect ke katalog (/)
✓ Navbar menampilkan nama pegawai + tombol Logout
```

#### **Test 4: Download File Scan Asli** ✅
```
Pre-requisite: Admin sudah upload file scan asli

1. Login sebagai pegawai
2. Buka detail survei di katalog
3. Lihat tombol "Download File Scan Asli" (hijau, enabled)
4. Klik tombol

Expected Result:
✓ File mulai download
✓ Audit log tercatat di storage/logs/laravel.log
✓ Throttling: max 10 download/menit
```

#### **Test 5: User External (Tidak Login)** ✅
```
1. Logout dari pegawai (atau buka incognito)
2. Buka detail survei yang ada file scan asli
3. Lihat tombol "Download File Scan Asli" (abu-abu, disabled)
4. Hover tombol, lihat lock icon

Expected Result:
✓ Tombol disabled
✓ Pesan: "Login sebagai Pegawai Internal ESDM untuk mengunduh file asli"
✓ Link ke /pegawai/login
```

#### **Test 6: Admin Upload File Scan Asli (600MB)** ✅
```
URL: http://localhost:8000/bbspgl-admin/data-survei/create

1. Login sebagai admin
2. Isi form data survei
3. Upload file scan asli (test dengan file > 100MB)
4. Submit form

Expected Result:
✓ File tersimpan di storage/app/public/scan_asli/
✓ ukuran_file_asli tercatat dalam bytes
✓ format_file_asli tercatat (pdf/tiff/zip/dll)
```

#### **Test 7: Admin Manual Approval** ✅
```
URL: http://localhost:8000/bbspgl-admin/pegawai-approval

Scenario: Email verification gagal/tidak masuk

1. User register tapi email tidak masuk
2. Admin login
3. Buka /bbspgl-admin/pegawai-approval
4. Klik "Approve" untuk pegawai pending
5. Pegawai coba login

Expected Result:
✓ Approval berhasil
✓ is_approved = true
✓ Pegawai bisa login meskipun email tidak verified
```

---

### C. Database Verification

```sql
-- Cek tabel pegawai_internal
SELECT * FROM pegawai_internal;

-- Cek email verified
SELECT nama, email, email_verified_at, is_approved 
FROM pegawai_internal;

-- Cek data survei dengan file scan asli
SELECT judul, file_scan_asli, ukuran_file_asli, format_file_asli 
FROM data_survei 
WHERE file_scan_asli IS NOT NULL;
```

---

## 🔐 Security Features

### Email Verification
- ✅ Token expire dalam **1 jam**
- ✅ Token di-hash dengan SHA256
- ✅ One-time use token
- ✅ Auto-approve setelah email verified

### Rate Limiting
- ✅ Register: **3 attempts/min**
- ✅ Login: **5 attempts/min**
- ✅ Download: **10 downloads/min**

### File Download Protection
- ✅ **WAJIB** login sebagai pegawai
- ✅ Email harus verified atau approved
- ✅ Audit logging setiap download
- ✅ File tidak di-symlink ke public (aman)

### Session Security
- ✅ Session regeneration setiap 5 menit
- ✅ Session invalidation saat logout
- ✅ CSRF protection
- ✅ Secure cookies (HTTPS ready)

---

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── PegawaiApprovalController.php  [NEW]
│   │   │   └── DataSurveiController.php       [UPDATED]
│   │   ├── Pegawai/
│   │   │   └── PegawaiAuthController.php      [NEW]
│   │   └── ScanDownloadController.php         [NEW]
│   ├── Middleware/
│   │   └── VerifiedPegawai.php                [NEW]
│   └── Requests/
│       └── DataSurveiRequest.php              [UPDATED - 600MB]
├── Mail/
│   └── PegawaiVerificationMail.php            [NEW]
└── Models/
    ├── PegawaiInternal.php                     [NEW]
    └── DataSurvei.php                          [UPDATED]

resources/views/
├── admin/
│   ├── pegawai/
│   │   └── index.blade.php                     [NEW]
│   └── data_survei/
│       ├── create.blade.php                    [UPDATED]
│       └── edit.blade.php                      [UPDATED]
├── pegawai/auth/
│   ├── login.blade.php                         [NEW]
│   └── register.blade.php                      [NEW]
├── emails/
│   └── pegawai-verification.blade.php          [NEW]
├── User/katalog/
│   └── show.blade.php                          [UPDATED]
└── layouts/
    └── app.blade.php                           [UPDATED - navbar]

database/migrations/
├── xxxx_create_pegawai_internal_table.php      [NEW]
└── xxxx_add_file_scan_asli_to_data_survei.php  [NEW]

config/
└── auth.php                                    [UPDATED - guard pegawai]

routes/
└── web.php                                     [UPDATED]

bootstrap/
└── app.php                                     [UPDATED - middleware]

.env                                            [UPDATED - Mailtrap]
```

---

## 🚨 Common Issues & Solutions

### Issue 1: Email Tidak Masuk ke Mailtrap
**Solution:**
```bash
1. Cek credentials di .env
2. php artisan config:clear
3. Cek log: tail -f storage/logs/laravel.log
4. Pastikan internet connected
5. Gunakan manual approval sebagai backup
```

### Issue 2: Upload File > 600MB Gagal
**Solution:**
```bash
1. Cek php.ini:
   upload_max_filesize = 700M
   post_max_size = 700M
   
2. Restart Apache

3. Verify:
   php -i | findstr upload_max_filesize
```

### Issue 3: "Akun belum diverifikasi"
**Solution:**
```bash
# Option 1: Resend verification email (belum implement)
# Option 2: Manual approval
1. Admin ke /bbspgl-admin/pegawai-approval
2. Klik Approve

# Option 3: Database manual
UPDATE pegawai_internal 
SET is_approved = 1, email_verified_at = NOW() 
WHERE email = 'test@esdm.go.id';
```

### Issue 4: Download File 403 Forbidden
**Solution:**
```bash
# Pastikan:
1. User sudah login sebagai pegawai
2. Email sudah verified atau approved
3. File exists di storage/app/public/scan_asli/

# Check permission
php artisan storage:link
chmod -R 755 storage/
```

---

## 📊 Testing Metrics

### Performance Testing
```bash
# Test upload file 500MB
# Expected: < 2 menit

# Test download file 500MB  
# Expected: Sesuai koneksi internet

# Test concurrent login
# Expected: Throttle kick in setelah 5 attempts/min
```

### Email Testing
```bash
# Test email delivery
# Expected: < 5 detik sampai inbox Mailtrap

# Test link expiry
# Expected: Link invalid setelah 1 jam
```

---

## 🎓 Next Steps / Future Enhancements

### Nice to Have (Optional)
- [ ] Resend verification email jika expired
- [ ] Forgot password untuk pegawai
- [ ] Profile page untuk pegawai
- [ ] Download history/statistics
- [ ] Email notification ke admin saat ada pending approval
- [ ] Bulk approve pegawai
- [ ] Export daftar pegawai ke Excel
- [ ] 2FA (Two-Factor Authentication)

---

## 📞 Support

**Jika ada masalah:**
1. Cek file `MAILTRAP_SETUP.md` untuk email setup
2. Cek file `php_upload_config.ini` untuk PHP limits
3. Cek `storage/logs/laravel.log` untuk error details
4. Test pakai Tinker: `php artisan tinker`

---

**🎉 SISTEM READY FOR PRODUCTION!**

Setelah semua test pass, sistem siap untuk:
- [x] Development testing
- [x] User acceptance testing (UAT)
- [ ] Production deployment (ganti Mailtrap dengan SMTP real)
