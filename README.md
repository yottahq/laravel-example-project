# Laravel Example Project

This is an example Laravel project that explains using the [Laravel Extended Auth](https://github.com/yottahq/laravel-extended-auth) package and it's features.


## How to run

1. Clone the project

```shell
git clone https://github.com/yottahq/laravel-example-project.git
```

2. Move to the project directory and install dependencies

```shell
cd laravel-example-project
composer install
npm install && npm run build
```

3. Copy .env.example to .env and update the database and email configuration

```shell
cp .env.example .env
```

4.Run the project

```shell
php artisan migrate
php artisan serve
```
