# Laravel Project Setup Guide

## 1. Clone Repository
Jalankan perintah berikut untuk meng-clone repository dari GitHub:
```bash
git clone https://github.com/pacong12/SIAKAD.git
```

Setelah cloning selesai, masuk ke direktori proyek:
```bash
cd SIAKAD
```

## 2. Install Dependencies
Jalankan perintah berikut untuk menginstal semua dependency Laravel:
```bash
composer install
```


## 3. Konfigurasi File `.env`
Buat file `.env` dengan menyalin dari `.env.example`:
```bash
cp .env.example .env
```
Kemudian buka file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```
Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai dengan database lokal Anda.

## 4. Generate Key
Laravel membutuhkan application key. Jalankan perintah berikut:
```bash
php artisan key:generate
```

## 5. Migrasi dan Seeding Database
Jalankan perintah berikut untuk menjalankan migrasi database:
```bash
php artisan migrate
```
Jika terdapat seeder, jalankan perintah:
```bash
php artisan db:seed
```
Atau untuk migrasi dan seeding sekaligus:
```bash
php artisan migrate --seed
```

## 6. Buat Storage Link
Untuk memastikan Laravel dapat mengakses file di storage, jalankan:
```bash
php artisan storage:link
```

## 7. Jalankan Server
Untuk menjalankan aplikasi Laravel secara lokal, gunakan perintah:
```bash
php artisan serve
```
Akses aplikasi di browser:
```
http://127.0.0.1:8000
```


