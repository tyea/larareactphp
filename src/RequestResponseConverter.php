<?php

namespace Tyea\LaraReactPhp;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use React\Http\Response as ReactPhpResponse;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Http\Response as LaravelResponse;
use Illuminate\Support\Facades\Config;

class RequestResponseConverter
{
	private function __construct()
	{
	}
	
	public static function convertRequest(ReactPhpRequest $reactPhpRequest): LaravelRequest
	{
		$path = $reactPhpRequest->getUri()->getPath();
		$query = $reactPhpRequest->getUri()->getQuery();
		$server = array_merge(
			$reactPhpRequest->getServerParams() ?? [],
			[
				"SERVER_PROTOCOL" => "HTTP/" . $reactPhpRequest->getProtocolVersion(),
				"SERVER_NAME" => $reactPhpRequest->getUri()->getHost(),
				"REQUEST_METHOD" => $reactPhpRequest->getMethod(),
				"REQUEST_URI" => strlen($query) > 0 ? ($path . "?" . $query) : $path,
				"QUERY_STRING" => $query,
				"PATH_INFO" => $path,
				"SCRIPT_NAME" => "/index.php",
				"SCRIPT_FILENAME" => Config::get("filesystems.disks.public.root") . "/index.php",
				"PHP_SELF" => "/index.php" . $path
			]
		);
		$laravelRequest = new LaravelRequest(
			$reactPhpRequest->getQueryParams(), // $_GET
			$reactPhpRequest->getParsedBody() ?? [], // $_POST
			$reactPhpRequest->getAttributes(), // []
			$reactPhpRequest->getCookieParams(), // $_COOKIE
			$reactPhpRequest->getUploadedFiles(), // $_FILES
			$server, // $_SERVER
			$reactPhpRequest->getBody() // STDIN
		);
		return $laravelRequest;
	}
}
