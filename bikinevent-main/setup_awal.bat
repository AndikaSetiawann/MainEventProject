@echo off
echo ========================================
echo   BikinEvent.my.id - Setup Awal
echo ========================================
echo.

echo [1/4] Checking Composer installation...
composer --version
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Composer tidak ditemukan!
    echo Download dari: https://getcomposer.org/download/
    echo.
    pause
    exit /b 1
)

echo.
echo [2/4] Installing dependencies...
cd sertif
composer install
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Gagal install dependencies!
    echo.
    pause
    exit /b 1
)

echo.
echo [3/4] Creating upload directories...
cd public
if not exist "uploads\institutions" mkdir uploads\institutions
if not exist "uploads\signatures" mkdir uploads\signatures
if not exist "uploads\temp" mkdir uploads\temp
echo Upload directories created successfully!

cd ..\..

echo.
echo [4/4] Setup completed!
echo.
echo ========================================
echo   LANGKAH SELANJUTNYA:
echo ========================================
echo.
echo 1. Import database menggunakan file: database_setup.sql
echo    - Buka phpMyAdmin
echo    - Klik tab SQL
echo    - Copy paste isi file database_setup.sql
echo    - Klik Go
echo.
echo 2. Sesuaikan konfigurasi database di: sertif\.env
echo    (jika MySQL Anda menggunakan password)
echo.
echo 3. Jalankan aplikasi dengan double-click: jalankan_aplikasi.bat
echo.
echo 4. Buka browser: http://localhost:8080
echo.
echo 5. Login dengan:
echo    Admin: admin@bikinevent.my.id / admin123
echo    Peserta: peserta@bikinevent.my.id / peserta123
echo.
echo ========================================
echo.
pause

