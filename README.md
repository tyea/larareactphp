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

You can optionally pass IP address and port arguments too.

```
php artisan serve:reactphp 0.0.0.0 80
```

## Development

LaraReactPHP ships with a Bash script for automatically rerunning the `php artisan serve:reactphp` command upon changes to your application.

```
bash vendor/bin/larareactphp:watch
```

You must have `inotify-tools` installed in order to use it.

## Notes

* Terminable middleware is not supported

## History

This repo is forked from:

* [mnvx/laravel-reactphp](https://github.com/mnvx/laravel-reactphp)
* [Saoneth/laravel-reactphp](https://github.com/Saoneth/laravel-reactphp)
* [rayout/laravel-reactphp](https://github.com/rayout/laravel-reactphp)
* [nazo/laravel-reactphp](https://github.com/nazo/laravel-reactphp)

## Author

Written by Tom Yeadon in March 2020.
