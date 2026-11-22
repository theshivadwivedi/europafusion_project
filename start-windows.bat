@echo off
rem ==========================================================================
rem  EUROPA FUSION - start the website on http://127.0.0.1:8000
rem ==========================================================================
setlocal DisableDelayedExpansion
cd /d "%~dp0"

if not exist ".env" call "%~dp0setup-windows.bat"
if not exist ".env" exit /b 1

echo.
echo  EUROPA FUSION website:  http://127.0.0.1:8000
echo  Admin panel:            http://127.0.0.1:8000/admin
echo.
echo  Keep this window open while you use the site. Press Ctrl+C to stop it.
echo  (If port 8000 is busy, the address below shows the port actually used.)
echo.

rem Open the browser after a short pause, while the server starts.
start "" /min cmd /c "ping -n 4 127.0.0.1 >nul & start "" http://127.0.0.1:8000"

php artisan serve --host=127.0.0.1 --port=8000

pause
