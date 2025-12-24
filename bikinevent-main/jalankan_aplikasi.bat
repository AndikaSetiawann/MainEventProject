@echo off
echo ========================================
echo   BikinEvent.my.id - Event Management
echo   Starting Development Server...
echo ========================================
echo.

cd sertif

echo Checking PHP installation...
php --version
if %errorlevel% neq 0 (
    echo.
    echo ERROR: PHP tidak ditemukan!
    echo Pastikan PHP sudah terinstall dan ditambahkan ke PATH
    echo.
    pause
    exit /b 1
)

echo.
echo Starting server at http://localhost:8080
echo.
echo CTRL+C untuk menghentikan server
echo ========================================
echo.

php spark serve

pause

