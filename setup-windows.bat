@echo off
rem ==========================================================================
rem  EUROPA FUSION - one-time setup on Windows
rem  The vendor\ folder is included: no Composer, Node.js or database needed.
rem ==========================================================================
setlocal DisableDelayedExpansion
cd /d "%~dp0"

where php >nul 2>nul
if errorlevel 1 goto nophp

php -r "exit(version_compare(PHP_VERSION, '8.3.0', '>=') ? 0 : 1);"
if errorlevel 1 goto oldphp

php -r "exit(extension_loaded('openssl') ? 0 : 1);"
if errorlevel 1 goto noopenssl

php -r "foreach (['mbstring', 'fileinfo'] as $e) { if (! extension_loaded($e)) { echo 'Note: the PHP extension '.$e.' is not enabled (recommended: extension='.$e.' in php.ini).'.PHP_EOL; } }"

if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo Created .env from .env.example
)

rem Generate the application key only when .env does not have one yet.
php -r "exit(preg_match('/^APP_KEY=\S/m', file_get_contents('.env')) ? 1 : 0);"
if not errorlevel 1 php artisan key:generate --force

php artisan optimize:clear >nul

echo.
set "MAKEADMIN="
set /p MAKEADMIN=Create a login for the admin panel (/admin) now? [y/N]:
if /i "%MAKEADMIN%"=="y" php artisan admin:create

echo.
echo Setup complete. Double-click start-windows.bat to run the website.
echo.
pause
exit /b 0

:nophp
echo.
echo PHP was not found.
echo Install PHP 8.3 or newer - for example XAMPP, Laragon or https://windows.php.net -
echo make sure "php -v" works in a Command Prompt, then run this file again.
echo See START-HERE-WINDOWS.txt for details.
echo.
pause
exit /b 1

:oldphp
echo.
echo PHP 8.3 or newer is required. This computer has:
php -v
echo Please install a newer PHP and run this file again.
echo.
pause
exit /b 1

:noopenssl
echo.
echo The PHP extension "openssl" is not enabled.
echo Open your php.ini, remove the ; in front of  extension=openssl
echo (also enable extension=mbstring and extension=fileinfo), save, and run this file again.
echo php.ini in use:
php --ini
echo.
pause
exit /b 1
