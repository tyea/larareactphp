# LaraReactPHP

## About

LaraReactPHP is a Laravel package for using ReactPHP to serve your application.

## Requirements

* PHP >= 7.0
* Laravel >= 5.5

## Installation

```
composer require tyea/larareactphp
```

## Usage

```
php artisan serve:reactphp
```

You can optionally pass host and port arguments.

```
php artisan serve:reactphp 0.0.0.0 80
```

## Development

This package ships with a Bash script for automatically rerunning the `php artisan serve:reactphp` command upon changes to your application.

```
bash vendor/bin/larareactphp
```

You can also optionally pass host and port arguments.

```
bash vendor/bin/larareactphp 127.0.0.1 8080
```

You must have `inotify-tools` installed in order to use it.

## State

This package allows you to handle the way state is reset between requests.

```
use Tyea\LaraReactPhp\Handlers\StateHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

StateHandler::set(function () {
	Auth::logout();
	Session::flush();
});
```

You should place this in the `AppServiceProvider` class in the `boot` method.

## Notes

* Terminable middleware is not supported
* File downloads are not supported
* File responses are not supported

## History

This repo is forked from:

* [mnvx/laravel-reactphp](https://github.com/mnvx/laravel-reactphp)
* [Saoneth/laravel-reactphp](https://github.com/Saoneth/laravel-reactphp)
* [rayout/laravel-reactphp](https://github.com/rayout/laravel-reactphp)
* [nazo/laravel-reactphp](https://github.com/nazo/laravel-reactphp)

## Author

Written by Tom Yeadon in March 2020.
