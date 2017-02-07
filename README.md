shakahl/laravel-reactphp
=========================

Integration of [ReactPHP](https://github.com/reactphp/react) Server for [Laravel 5.3](http://laravel.com) and [Lumen 5.3](http://lumen.laravel.com).

## Installation

- Install via composer

```sh
composer require shakahl/laravel-reactphp
```

- After installing, add provider on config/app.php on your project.

```php
// app.php

    'providers' => [
        ...

        'LaravelReactPHP\Providers\ReactCommandProvider',
    ],
```

## Run the server

```sh
php artisan react-serve --host=localhost --port=8080
```

## Credits

This library is forked from [Saoneth/laravel-reactphp](https://github.com/Saoneth/laravel-reactphp).
