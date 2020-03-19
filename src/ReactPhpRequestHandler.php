<?php

namespace Tyea\LaraReactPhp;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use React\Http\Response as ReactPhpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Http\Response as LaravelResponse;

class ReactPhpRequestHandler
{
	private function __construct()
	{
	}
	
	// @todo use promises
	// @todo serve static files
	// @todo investigate terminable middleware
	public static function handle(ReactPhpRequest $reactPhpRequest): ReactPhpResponse
	{
		$kernel = App::make("Illuminate\\Contracts\\Http\\Kernel");
		$laravelRequest = RequestResponseConverter::convertRequest($reactPhpRequest);
		$laravelResponse = $kernel->handle($laravelRequest);
		$reactPhpResponse = RequestResponseConverter::convertResponse($laravelResponse);
		return $reactPhpResponse;
	}
}
