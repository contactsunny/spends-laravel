![Build Status](https://gitlab.com/contactsunny/spends-laravel/badges/master/build.svg)
![Maintainer](https://img.shields.io/badge/maintainer-contactsunny-blue.svg)
![Framework](https://img.shields.io/badge/framework-Laravel-orange.svg)
![Source](https://img.shields.io/badge/source-closed-red.svg)

## Setup

```sh
mkdir -p ~/code/spends-laravel
cd ~/code/spends-laravel
git clone git@gitlab.com:contactsunny/spends-laravel.git .
cp .env.example .env
```
Update DB credentials and APP_KEY in the .env file.

Install [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) and run the command below.

```sh
composer install
```

**Running app in development**

```
php artisan migrate
php artisan serve
```