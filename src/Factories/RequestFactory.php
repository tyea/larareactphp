<?php

namespace Tyea\LaraReactPhp\Factories;

use React\Http\Io\ServerRequest as ReactPhpRequest;
use Illuminate\Http\Request as LaravelRequest;

class RequestFactory
{
	private function __construct()
	{
	}
	
	public static function makeFromRequest(ReactPhpRequest $reactPhpRequest): LaravelRequest
	{
		$get = $reactPhpRequest->getQueryParams() ?? [];
		$post = $reactPhpRequest->getParsedBody() ?? [];
		$attributes = $reactPhpRequest->getAttributes() ?? [];
		$cookie = $reactPhpRequest->getCookieParams() ?? [];
		$files = $reactPhpRequest->getUploadedFiles() ?? [];
		$server = $reactPhpRequest->getServerParams() ?? [];
		$server["SERVER_PROTOCOL"] = "HTTP/" . $reactPhpRequest->getProtocolVersion();
		$server["SERVER_NAME"] = $reactPhpRequest->getUri()->getHost();
		$server["REQUEST_METHOD"] = $reactPhpRequest->getMethod();
		$server["PATH_INFO"] = $reactPhpRequest->getUri()->getPath();
		$server["QUERY_STRING"] = $reactPhpRequest->getUri()->getQuery();
		$server["REQUEST_URI"] =
			strlen($reactPhpRequest->getUri()->getQuery()) > 0 ?
			($reactPhpRequest->getUri()->getPath() . "?" . $reactPhpRequest->getUri()->getQuery()) :
			$reactPhpRequest->getUri()->getPath();
		$server["SCRIPT_NAME" = "/index.php";
		$server["SCRIPT_FILENAME" = public_path() . "/index.php";
		$server["PHP_SELF" = "/index.php" . $reactPhpRequest->getUri()->getPath();
		foreach ($reactPhpRequest->getHeaders() as $header => $values) {
			$key = "HTTP_" . strtoupper(str_replace("-", "_", $header));
			$server[$key] = implode(", ", $values);
		}
		if (array_key_exists("HTTP_CONTENT_TYPE", $server)) {
			$server["CONTENT_TYPE"] = $server["HTTP_CONTENT_TYPE"];
		}
		if (array_key_exists("HTTP_CONTENT_LENGTH", $server)) {
			$server["CONTENT_LENGTH"] = $server["HTTP_CONTENT_LENGTH"];
		}
		$content = $reactPhpRequest->getBody()->getContents() ?? "";
		return new LaravelRequest($get, $post, $attributes, $cookie, $files, $server, $content);
	}
}
