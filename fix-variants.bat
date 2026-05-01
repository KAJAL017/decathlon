@echo off
echo =========================================
echo Fixing Variant Attributes Issue
echo =========================================
echo.

REM Step 1: Run the seeder
echo Step 1: Running AttributeModuleSeeder...
php artisan db:seed --class=AttributeModuleSeeder
echo Done!
echo.

REM Step 2: Clear cache
echo Step 2: Clearing cache...
php artisan cache:clear
php artisan config:clear
echo Done!
echo.

echo =========================================
echo Fix Complete!
echo =========================================
echo.
echo Next steps:
echo 1. Refresh your browser
echo 2. Go to Products -^> Add Product -^> Variants tab
echo 3. Click 'Generate Variants'
echo 4. You should see Color and Size attributes
echo.
pause
