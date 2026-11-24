# POS Customer and User Accounts

Laravel application for IT0049 TFA6 and TFA7. Includes customer and user listings, creation and editing, validation and avatar uploads. Five sample records per table are seeded. Intervention Image creates 256 x 256 JPEG avatars. Confirm this image package is approved by your instructor. This is an educational example and does not include account login or access control.

## Local setup on Windows

Requires PHP 8.4 or newer with GD, SQLite, mbstring, fileinfo, OpenSSL and ZIP extensions, and Composer.

1. Extract the project and open a terminal inside the project folder.
2. Run `composer install`.
3. Run `copy .env.example .env` in Command Prompt (or `Copy-Item .env.example .env` in PowerShell).
4. Run `php artisan key:generate`.
5. Ensure `.env` has `DB_CONNECTION=sqlite`. Create an empty `database/database.sqlite` file.
6. Run `php artisan migrate --seed`, followed by `php artisan storage:link`.
7. Run `php artisan serve` and visit the URL printed by Laravel.

The seeder uses updateOrCreate for its sample records. Do not run `migrate:fresh` against an existing database you want to keep.

## Deployment

Use PHP 8.4+, GD and a persistent database. Use persistent object storage or a mounted volume for uploaded avatars: container storage may disappear at redeploy. Set APP_KEY privately, APP_ENV=production and APP_DEBUG=false. Never commit .env or credentials. Run migrations and seed the sample data on the target database. If using the local public disk, create the storage link and ensure the storage path is writable and persistent.

This project uses Intervention Image with the GD driver; instructor approval is not confirmed.
