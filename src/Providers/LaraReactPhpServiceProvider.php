<?php

namespace Tyea\LaraReactPhp\Providers;

use Illuminate\Support\ServiceProvider;

class LaraReactPhpServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->commands([
			"Tyea\\LaraReactPhp\\Console\\Commands\\ServeReactPhpCommand"
		]);
	}
}
