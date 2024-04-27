# Perkenalan
Projek ini merupakan dasar dari penggunaan laravel dan livewire. terdapat beberapa komponen dasar seperti tabel yang teritegrasi dengan tombol `edit` dan `delete`, selain itu juga telah terintegrasi dengan penggunaan *permission* dari paket `spatie/laravel-permission`. sebagai dasar dari penggunaan fitur `create`, `read`, `update`, dan `delete` terdapat sebuah tabel dan modal dari model `\App\Models\User` yang dapat dipelajari.

# Kebutuhan Projek
- [Git](https://git-scm.com)
- [PHP 8.3](https://php.net)
- [MariaDB](https://mariadb.org)
- [Composer](https://getcomposer.org)
- [NodeJS](https://nodejs.org)
> Jika menggunakan Windows, sangat disarankan menggunakan [Laragon](https://laragon.net).

# Instalasi Projek
- Clone repository projek ini.
```sh
$ git clone https://github.com/rif746/laravel-livewire-base.git
# atau
$ git clone https://gitlab.com/rif746/livewire-base.git
```
- Masuk ke dalam direktori, dan pasang dependensi menggunakan `composer` dan `npm`.
```sh
$ composer install
$ npm install
```
- Jalankan projek.
```sh
$ php artisan serve
```
- Jalankan Vite untuk meng-*compile* CSS dan JS
```sh
$ npm run dev
```