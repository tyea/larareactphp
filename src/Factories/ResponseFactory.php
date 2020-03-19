<?php

namespace Tyea\LaraReactPhp\Factories;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use React\Http\Response as ReactPhpResponse;
use Illuminate\Http\Response as LaravelResponse;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;

class ResponseFactory
{
	private function __construct()
	{
	}
	
	public static function makeFromResponse(LaravelResponse $laravelResponse): ReactPhpResponse
	{
		return ReactPhpResponse(
			$laravelResponse->getStatusCode(),
			$laravelResponse->headers->all(),
			$laravelResponse->getContent()
		);
	}
	
	public static function makeFromPath(ReactPhpRequest $reactPhpRequest): ReactPhpResponse
	{
		$extension = pathinfo($reactPhpRequest->getUri()->getPath(), PATHINFO_EXTENSION);
		$mimeType = (new MimeTypes())->getMimeType($extension) ?? "text/plain";
		$headers = [
			"Content-Type" => $mimeType
		];
		$content = Storage::disk("reactphp")->get($reactPhpRequest->getUri()->getPath());
		return new ReactPhpResponse(200, $headers, $content);
	}
}
