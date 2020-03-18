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
	public static function handle(ReactPhpRequest $reactPhpRequest): ReactPhpResponse
	{
		$kernel = App::make("Illuminate\\Contracts\\Http\\Kernel");
		$laravelRequest = RequestResponseConverter::convertRequest($reactPhpRequest);
		$laravelResponse = $kernel->handle($laravelRequest);
		// @todo convert response
		// @todo send response
		// $response->send();
		// @todo terminate
		// $kernel->terminate($request, $response);
		$reactPhpResponse = new ReactPhpResponse(200, ["Content-Type" => "text/plain"], var_export($laravelResponse, true));
		return $reactPhpResponse;
	}
}
