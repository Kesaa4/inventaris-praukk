@echo off
REM Script untuk mempersiapkan aplikasi untuk deployment (Windows)
REM Jalankan: deploy-prepare.bat

echo ==========================================
echo   PERSIAPAN DEPLOYMENT PRODUCTION
echo ==========================================
echo.

REM 1. Install dependencies production
echo [1/5] Installing production dependencies...
call composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo Error: Failed to install dependencies
    pause
    exit /b 1
)
echo Done: Dependencies installed
echo.

REM 2. Clear cache
echo [2/5] Clearing cache...
php spark cache:clear
echo Done: Cache cleared
echo.

REM 3. Generate encryption key
echo [3/5] Generating encryption key...
echo Copy key ini ke .env di production:
php spark key:generate --show
echo.

REM 4. Reminder untuk test
echo [4/5] Testing reminder...
echo Pastikan CI_ENVIRONMENT=production di .env
echo Kemudian jalankan: php spark serve
echo.

REM 5. Create archive
echo [5/5] Creating deployment archive...
set timestamp=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set timestamp=%timestamp: =0%
powershell Compress-Archive -Path * -DestinationPath inventaris_%timestamp%.zip -Force -Exclude .git,.env,writable\logs\*,writable\cache\*,*.sh,node_modules\*,tests\*
if %errorlevel% neq 0 (
    echo Warning: Archive creation failed
) else (
    echo Done: Archive created successfully
)
echo.

echo ==========================================
echo   PERSIAPAN SELESAI!
echo ==========================================
echo.
echo Langkah selanjutnya:
echo 1. Upload file zip ke hosting
echo 2. Extract di hosting
echo 3. Copy .env.production menjadi .env
echo 4. Edit .env dengan kredensial database
echo 5. Export dan import database
echo 6. Test aplikasi
echo.
echo Lihat DEPLOYMENT_CHECKLIST.md untuk panduan lengkap
echo.
pause
