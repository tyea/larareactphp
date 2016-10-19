# laravel-reactphp

ReactPHP Server on Laravel 5.3

## install

- install via composer

```sh
composer require Saoneth/laravel-reactphp
```

- After installing, add provider on config/app.php on your project.

```php
// app.php

    'providers' => [
        ...

        'LaravelReactPHP\Providers\ReactCommandProvider',
    ],
```

## run server

```sh
php artisan react-serve
```

