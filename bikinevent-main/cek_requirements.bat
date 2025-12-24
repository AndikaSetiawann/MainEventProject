@echo off
echo ========================================
echo   BikinEvent.my.id - System Check
echo ========================================
echo.

echo Checking system requirements...
echo.

REM Check PHP
echo [1/3] Checking PHP...
php --version
if %errorlevel% neq 0 (
    echo    [X] PHP NOT FOUND!
    echo    Download: https://www.php.net/downloads
    set PHP_OK=0
) else (
    echo    [OK] PHP is installed
    set PHP_OK=1
)
echo.

REM Check Composer
echo [2/3] Checking Composer...
composer --version
if %errorlevel% neq 0 (
    echo    [X] Composer NOT FOUND!
    echo    Download: https://getcomposer.org/download/
    set COMPOSER_OK=0
) else (
    echo    [OK] Composer is installed
    set COMPOSER_OK=1
)
echo.

REM Check MySQL (via mysqladmin or mysql)
echo [3/3] Checking MySQL...
mysql --version
if %errorlevel% neq 0 (
    echo    [!] MySQL command not found in PATH
    echo    Make sure XAMPP/WAMP/MySQL is installed and running
    set MYSQL_OK=0
) else (
    echo    [OK] MySQL is installed
    set MYSQL_OK=1
)
echo.

echo ========================================
echo   SUMMARY
echo ========================================
echo.

if %PHP_OK%==1 if %COMPOSER_OK%==1 (
    echo [OK] All requirements met!
    echo.
    echo You can proceed with setup:
    echo 1. Run: setup_awal.bat
    echo 2. Import database: database_setup.sql
    echo 3. Run: jalankan_aplikasi.bat
) else (
    echo [!] Some requirements are missing!
    echo Please install missing components first.
)

echo.
echo ========================================
echo.

REM Check PHP Extensions
if %PHP_OK%==1 (
    echo Checking PHP Extensions...
    echo.
    php -m | findstr /i "intl mbstring json mysqlnd xml gd"
    echo.
    echo If extensions above are not shown, you may need to enable them in php.ini
    echo.
)

pause

