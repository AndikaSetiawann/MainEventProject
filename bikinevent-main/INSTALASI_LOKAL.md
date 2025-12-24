# 📋 PANDUAN INSTALASI LOKAL - BikinEvent.my.id

Panduan lengkap untuk menjalankan aplikasi Event Management & Certificate System di komputer lokal Anda.

---

## 📌 Persyaratan Sistem

Pastikan komputer Anda sudah terinstal:

- ✅ **PHP 8.1 atau lebih tinggi**
- ✅ **MySQL 8.0 atau MariaDB 10.4+**
- ✅ **Composer** (untuk dependency management)
- ✅ **Web Server** (Apache/Nginx) atau gunakan PHP Built-in Server
- ✅ **Extension PHP yang diperlukan:**
  - intl
  - mbstring
  - json
  - mysqlnd
  - xml
  - gd (untuk manipulasi gambar)

---

## 🚀 Langkah-Langkah Instalasi

### 1️⃣ Setup Database

#### Opsi A: Menggunakan phpMyAdmin
1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Klik tab **"SQL"**
3. Copy seluruh isi file `database_setup.sql`
4. Paste ke kolom SQL dan klik **"Go"**

#### Opsi B: Menggunakan Command Line
```bash
# Masuk ke MySQL
mysql -u root -p

# Jalankan script SQL
source database_setup.sql

# Atau langsung dari command line
mysql -u root -p < database_setup.sql
```

### 2️⃣ Konfigurasi Environment

File `.env` sudah dibuat di folder `sertif/.env`. Sesuaikan konfigurasi database jika diperlukan:

```env
database.default.hostname = localhost
database.default.database = event_management
database.default.username = root
database.default.password = 
database.default.port = 3306
```

**Catatan:** Jika MySQL Anda menggunakan password, isi bagian `database.default.password`

### 3️⃣ Install Dependencies

Buka terminal/command prompt di folder project, lalu jalankan:

```bash
cd sertif
composer install
```

### 4️⃣ Setup Folder Upload

Pastikan folder upload sudah ada dan memiliki permission yang benar:

```bash
# Untuk Windows (jalankan di PowerShell/CMD)
cd sertif/public
mkdir uploads\institutions
mkdir uploads\signatures
mkdir uploads\temp

# Untuk Linux/Mac
cd sertif/public
mkdir -p uploads/institutions
mkdir -p uploads/signatures
mkdir -p uploads/temp
chmod -R 777 uploads
```

### 5️⃣ Jalankan Aplikasi

#### Opsi A: Menggunakan PHP Built-in Server (Recommended untuk Development)

```bash
# Dari folder root project
cd sertif
php spark serve
```

Aplikasi akan berjalan di: **http://localhost:8080**

#### Opsi B: Menggunakan XAMPP/WAMP

1. Copy folder project ke `htdocs` (XAMPP) atau `www` (WAMP)
2. Akses melalui browser: `http://localhost/bikinevent-main/`
3. **PENTING:** Jika menggunakan cara ini, edit file `sertif/.env`:
   ```env
   app.baseURL = 'http://localhost/bikinevent-main/'
   ```

---

## 👤 Akun Default

Setelah instalasi, Anda bisa login dengan akun berikut:

### Admin
- **Email:** admin@bikinevent.my.id
- **Password:** admin123

### Peserta
- **Email:** peserta@bikinevent.my.id
- **Password:** peserta123

---

## 🎯 Fitur Utama

### Untuk Admin:
- ✅ Dashboard dengan statistik real-time
- ✅ Kelola Event (Create, Read, Update, Delete)
- ✅ Kelola Peserta
- ✅ Generate Sertifikat PDF
- ✅ Laporan dan Analytics

### Untuk Peserta:
- ✅ Lihat dan Daftar Event
- ✅ Dashboard Peserta
- ✅ Download Sertifikat Digital (PDF)
- ✅ Kelola Profil

---

## 🔧 Troubleshooting

### Error: "Database connection failed"
**Solusi:**
1. Pastikan MySQL sudah running
2. Cek konfigurasi di `sertif/.env`
3. Pastikan database `event_management` sudah dibuat

### Error: "Class 'TCPDF' not found"
**Solusi:**
```bash
cd sertif
composer require tecnickcom/tcpdf
```

### Error: "Permission denied" saat upload file
**Solusi:**
```bash
# Windows: Pastikan folder tidak read-only
# Linux/Mac:
chmod -R 777 sertif/public/uploads
chmod -R 777 sertif/writable
```

### Halaman tidak muncul / Error 404
**Solusi:**
1. Pastikan file `.htaccess` ada di folder `sertif/public/`
2. Jika menggunakan Apache, pastikan `mod_rewrite` sudah aktif
3. Coba akses dengan: `http://localhost:8080/index.php/`

### Error: "Composer not found"
**Solusi:**
Download dan install Composer dari: https://getcomposer.org/download/

---

## 📁 Struktur Folder Penting

```
bikinevent-main/
├── sertif/                          # Aplikasi CodeIgniter 4
│   ├── app/
│   │   ├── Controllers/             # Controller files
│   │   ├── Models/                  # Model files
│   │   ├── Views/                   # View files
│   │   └── Config/                  # Konfigurasi
│   ├── public/
│   │   ├── uploads/                 # Folder upload
│   │   │   ├── institutions/        # Logo institusi
│   │   │   ├── signatures/          # Tanda tangan
│   │   │   └── temp/                # File temporary
│   │   ├── css/                     # CSS files
│   │   ├── assets/                  # Assets (JS, images)
│   │   └── index.php                # Entry point
│   ├── writable/                    # Cache, logs, session
│   ├── .env                         # Environment config
│   └── composer.json                # Dependencies
├── database_setup.sql               # SQL setup file
└── INSTALASI_LOKAL.md              # File ini
```

---

## 🔐 Keamanan

**PENTING untuk Production:**

1. **Ubah Environment ke Production**
   ```env
   CI_ENVIRONMENT = production
   ```

2. **Ganti Password Default**
   - Login sebagai admin
   - Ubah password di menu Profile

3. **Set Permission Folder yang Benar**
   ```bash
   chmod 755 sertif/public/uploads
   chmod 755 sertif/writable
   ```

4. **Aktifkan HTTPS**
   - Gunakan SSL certificate
   - Update baseURL ke `https://`

---

## 📞 Bantuan & Support

Jika mengalami masalah:
1. Cek file log di `sertif/writable/logs/`
2. Pastikan semua requirement terpenuhi
3. Cek dokumentasi CodeIgniter 4: https://codeigniter.com/user_guide/

---

## 📝 Catatan Tambahan

- Aplikasi ini menggunakan **CodeIgniter 4** framework
- Sertifikat di-generate menggunakan library **TCPDF**
- Database menggunakan **MySQL/MariaDB**
- Frontend menggunakan **Bootstrap 5**

---

**Selamat menggunakan BikinEvent.my.id! 🎉**

Dibuat dengan ❤️ untuk memudahkan pengelolaan event dan sertifikat digital.

