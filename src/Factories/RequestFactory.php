<?php

namespace Tyea\LaraReactPhp\Factories;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Support\Facades\Config;

class RequestFactory
{
	private function __construct()
	{
	}
	
	public static function makeFromRequest(ReactPhpRequest $reactPhpRequest): LaravelRequest
	{
		return new LaravelRequest(
			$reactPhpRequest->getQueryParams(),
			$reactPhpRequest->getParsedBody() ?? [],
			$reactPhpRequest->getAttributes(),
			$reactPhpRequest->getCookieParams(),
			$reactPhpRequest->getUploadedFiles(),
			array_merge(
				$reactPhpRequest->getServerParams() ?? [],
				[
					"SERVER_PROTOCOL" => "HTTP/" . $reactPhpRequest->getProtocolVersion(),
					"SERVER_NAME" => $reactPhpRequest->getUri()->getHost(),
					"REQUEST_METHOD" => $reactPhpRequest->getMethod(),
					"PATH_INFO" => $reactPhpRequest->getUri()->getPath(),
					"QUERY_STRING" => $reactPhpRequest->getUri()->getQuery(),
					"REQUEST_URI" =>
						strlen($reactPhpRequest->getUri()->getQuery()) > 0
						? ($reactPhpRequest->getUri()->getPath() . "?" . $reactPhpRequest->getUri()->getQuery())
						: $reactPhpRequest->getUri()->getPath(),
					"SCRIPT_NAME" => "/index.php",
					"SCRIPT_FILENAME" => Config::get("filesystems.disks.reactphp.root") . "/index.php",
					"PHP_SELF" => "/index.php" . $reactPhpRequest->getUri()->getPath()
				]
			),
			$reactPhpRequest->getBody()
		);
	}
}
