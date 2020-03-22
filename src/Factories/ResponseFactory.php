<?php

namespace Tyea\LaraReactPhp\Factories;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use React\Http\Response as ReactPhpResponse;
use Illuminate\Http\Response as LaravelResponse;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;

class ResponseFactory
{
	private function __construct()
	{
	}
	
	public static function makeFromFile(ReactPhpRequest $reactPhpRequest): ReactPhpResponse
	{
		return new ReactPhpResponse(
			200,
			[
				"Content-Type" => Storage::disk("reactphp")->mimeType($reactPhpRequest->getUri()->getPath()),
				"Last-Modified" => DateTime::createFromFormat(
					"U",
					Storage::disk("reactphp")->lastModified($reactPhpRequest->getUri()->getPath()),
					new DateTimeZone("UTC")
				)->format(DateTime::RFC7231)
			],
			Storage::disk("reactphp")->get($reactPhpRequest->getUri()->getPath())
		);
	}
	
	public static function makeFromResponse(LaravelResponse $laravelResponse): ReactPhpResponse
	{
		return new ReactPhpResponse(
			$laravelResponse->getStatusCode(),
			$laravelResponse->headers->all(),
			$laravelResponse->getContent()
		);
	}
}
