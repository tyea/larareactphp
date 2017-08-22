mnvx/laravel-reactphp
=========================

Integration of [ReactPHP](https://github.com/reactphp/http) Server for [Laravel 5.4](http://laravel.com) and [Lumen 5.4](http://lumen.laravel.com).

This fork works with requests contained JSON body.

Now using [reactphp/http](https://github.com/reactphp/http) 0.7.0!

## Installation

Install via composer

```sh
composer require mnvx/laravel-reactphp:dev-master
```

After installing, add provider on config/app.php on your project.

```php
// app.php

'providers' => [
    // ...
  
    'LaravelReactPHP\Providers\ReactCommandProvider',
],
```

## Run the server

```sh
php artisan react-serve --listen=tcp://127.0.0.1:8080
```

## Credits

This library based on [Saoneth/laravel-reactphp](https://github.com/Saoneth/laravel-reactphp).

- [reactphp/http](https://github.com/reactphp/http)
- [Saoneth/laravel-reactphp](https://github.com/Saoneth/laravel-reactphp)
- [zendframework/zend-diactoros](https://github.com/zendframework/zend-diactoros)
- [symfony/psr-http-message-bridge](https://github.com/symfony/psr-http-message-bridge)
- [laravel/lumen-framework](https://github.com/laravel/lumen-framework)
- [laravel/framework](https://github.com/laravel/framework)
