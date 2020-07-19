<?php

namespace Tyea\LaraReactPhp\Handlers;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use React\Http\Response as ReactPhpResponse;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Http\Response as LaravelResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tyea\LaraReactPhp\Factories\RequestFactory;
use Tyea\LaraReactPhp\Factories\ResponseFactory;
use React\Promise\Promise;

class HttpServerHandler
{
	private function __construct()
	{
	}

	public static function handle(ReactPhpRequest $reactPhpRequest): Promise
	{
		return new Promise(function ($resolve, $reject) use ($reactPhpRequest) {
			$pathExists = Storage::disk("reactphp")->exists($reactPhpRequest->getUri()->getPath());
			$isEntryPoint = Str::startsWith($reactPhpRequest->getUri()->getPath(), "/index.php");
			StateHandler::handle();
			if ($pathExists && !$isEntryPoint) {
				$reactPhpResponse = ResponseFactory::makeFromFile($reactPhpRequest);
			} else {
				$kernel = App::make("Illuminate\\Contracts\\Http\\Kernel");
				$laravelRequest = RequestFactory::makeFromRequest($reactPhpRequest);
				$laravelResponse = $kernel->handle($laravelRequest);
				$reactPhpResponse = ResponseFactory::makeFromResponse($laravelResponse);
			}
			$resolve($reactPhpResponse);
		});
	}
}
