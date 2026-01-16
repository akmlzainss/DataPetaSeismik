# 📧 MAILTRAP SETUP - TESTING EMAIL VERIFICATION

## Setup Mailtrap untuk Testing Email

### 1. Buat Akun Mailtrap (GRATIS)
1. Kunjungi: https://mailtrap.io/
2. Sign up gratis (bisa pakai Google/GitHub)
3. Verifikasi email Anda

### 2. Dapatkan Credentials SMTP
1. Login ke Mailtrap
2. Klik **"My Inbox"** atau **"Email Testing"**
3. Pilih **inbox** Anda (default: "My Inbox")
4. Klik tab **"SMTP Settings"**
5. Pilih **"Laravel 9+"** dari dropdown
6. Copy credentials yang ditampilkan

### 3. Update File `.env`

Ganti bagian MAIL di `.env` dengan credentials dari Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=f1234567890abc  # <- Ganti dengan username Mailtrap Anda
MAIL_PASSWORD=1234567890abc   # <- Ganti dengan password Mailtrap Anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bbspgl.esdm.go.id"
MAIL_FROM_NAME="BBSPGL - Sistem Informasi Survei Seismik"
```

### 4. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Testing Email Verification

### Test 1: Register Pegawai Baru
1. Buka browser: `http://localhost:8000/pegawai/daftar`
2. Isi form pendaftaran:
   - **Nama**: Test Pegawai
   - **Email**: test@esdm.go.id *(penting: harus @esdm.go.id)*
   - **Password**: password123
   - **Konfirmasi Password**: password123
3. Klik **"Daftar Sekarang"**

### Test 2: Cek Email di Mailtrap
1. Buka Mailtrap inbox Anda
2. Lihat email baru yang masuk (judul: "Verifikasi Email Pegawai Internal")
3. Klik link **"Verifikasi Email Sekarang"** di email
4. Anda akan redirect ke halaman login dengan pesan sukses

### Test 3: Login Pegawai
1. Login dengan email `test@esdm.go.id` dan password yang Anda buat
2. Setelah login, Anda akan bisa download file scan asli

---

## Troubleshooting

### Email Tidak Masuk ke Mailtrap?
1. **Cek credentials** di `.env` sudah benar
2. **Clear cache**: `php artisan config:clear`
3. **Cek log Laravel**: `storage/logs/laravel.log`
4. Pastikan tidak ada firewall yang block port 2525

### Link Verifikasi Expired?
- Link hanya valid **1 jam**
- Jika expired, minta admin untuk **manual approval**:
  - Admin login → `/bbspgl-admin/pegawai-approval`
  - Klik tombol **"Approve"** untuk pegawai tersebut

### Manual Approval (Backup Method)
Jika email tidak bisa dikirim sama sekali:
1. Admin login ke `/bbspgl-admin/pegawai-approval`
2. Lihat daftar pegawai pending
3. Klik **"Approve"** untuk meng-approve manual
4. Pegawai langsung bisa login tanpa verifikasi email

---

## Production Setup (Jika Deploy Ke Server Real)

Untuk production, **JANGAN pakai Mailtrap**. Gunakan:

### Option 1: Gmail SMTP (Mudah tapi ada limit)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Bukan password biasa!
MAIL_ENCRYPTION=tls
```
**Note**: Harus pakai **App Password**, bukan password Gmail biasa
- https://support.google.com/accounts/answer/185833

### Option 2: SendGrid (Recommended untuk production)
- Free tier: 100 email/hari
- Lebih reliable dari Gmail
- https://sendgrid.com/

### Option 3: Amazon SES (Paling murah untuk volume besar)
- $0.10 per 1000 email
- https://aws.amazon.com/ses/

---

## Testing Checklist

- [ ] Mailtrap account sudah dibuat
- [ ] Credentials sudah di-copy ke `.env`
- [ ] Config cache sudah di-clear
- [ ] Bisa register pegawai baru
- [ ] Email masuk ke Mailtrap inbox
- [ ] Link verifikasi berfungsi
- [ ] Bisa login setelah verifikasi
- [ ] Bisa download file scan asli setelah login
- [ ] Admin bisa approve manual jika diperlukan

---

**Need Help?**
- Mailtrap Docs: https://mailtrap.io/docs/
- Laravel Mail Docs: https://laravel.com/docs/mail
