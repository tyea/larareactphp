<?php

namespace LaravelReactPHP;

use App;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use \Symfony\Component\Console\Output\ConsoleOutput;

class HttpSession
{
  /**
   * @var string
   */
  protected $host;

  /**
   * @var int
   */
  protected $port;

  protected $output;
  protected $verbose;

  /**
   * @var string
   */
  protected $request_body;

  /**
   * @var array
   */
  protected $post_params;

  /**
   *
   *
   * @param string $host binding host
   * @param int $port binding port
   */
  public function __construct($host, $port, $verbose)
  {
    $this->host = $host;
    $this->port = $port;

    $this->output = new ConsoleOutput();
    $this->verbose = $verbose;
  }

  protected function info($message)
  {
    if ($this->verbose) {
      $this->output->writeln("<info>$message</info>");
    }
  }

  protected function log($message)
  {
    if ($this->verbose) {
      $this->output->writeln('    ' . $message);
    }
  }

  protected function getRequestUri(array $headers, $path)
  {
    $protocol = "http://";
    if (isset($headers['HTTPS'])) {
      $protocol = "https://";
    }
    $http_host = $protocol . $this->host;
    if (isset($headers['Host'])) {
      $http_host = $protocol . $headers['Host'];
    }

    return $http_host . $path;
  }

  protected function getCookies(array $headers)
  {
    $cookies = [];

    if (isset($headers['Cookie'])) {
      $cookies_tmp = explode('; ', $headers['Cookie']);
      foreach ($cookies_tmp as $cookie) {
        $data = \GuzzleHttp\Cookie\SetCookie::fromString($cookie)->toArray();
        $cookies[$data['Name']] = rawurldecode($data['Value']);
      }
    }
    return $cookies;
  }

  protected function buildCookies(array $cookies)
  {
    $headers = [];
    foreach ($cookies as $cookie) {
      if (!isset($headers['Set-Cookie'])) {
        $headers['Set-Cookie'] = [];
      }
      $cookie_value = sprintf("%s=%s", rawurlencode($cookie->getName()), rawurlencode($cookie->getValue()));
      if ($cookie->getDomain()) {
        $cookie_value .= sprintf("; Domain=%s", $cookie->getDomain());
      }
      if ($cookie->getExpiresTime()) {
        $cookie_value .= sprintf("; Max-Age=%s", $cookie->getExpiresTime());
      }
      if ($cookie->getPath()) {
        $cookie_value .= sprintf("; Path=%s", $cookie->getPath());
      }
      if ($cookie->isSecure()) {
        $cookie_value .= "; Secure";
      }
      if ($cookie->isHttpOnly()) {
        $cookie_value .= "; HttpOnly";
      }
      $headers['Set-Cookie'][] = $cookie_value;
    }

    return $headers;
  }

  protected function handleRequest(\React\Http\Request $request, \React\Http\Response $response)
  {
    $this->info($request->getMethod() . ' ' . $request->getPath());
    foreach (array_merge($request->getQuery(), $this->post_params) as $key => $value) {
      if (!is_string($value)) {
        $value = json_encode($value);
      }
      $this->log($key . ' => ' . $value);
    }

    $file_path = base_path('public') . (strpos($request->getPath(), "By") ? substr($request->getPath(), 0, strpos($request->getPath(), "?")) : $request->getPath());

    if ($request->getPath() != '/' && file_exists($file_path)) {
      header("X-Sendfile: " . basename($file_path));
      header("Content-type: " . mime_content_type($file_path));
      header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
      $response->writeHead(200);
      $response->end(file_get_contents($file_path));
      return;
    }

    $kernel = app();

    $laravelRequest = Request::create(
      $this->getRequestUri($request->getHeaders(), $request->getPath()),
      $request->getMethod(),
      array_merge($request->getQuery(), $this->post_params),
      $this->getCookies($request->getHeaders()),
      [],
      $request->getHeaders(),
      $this->request_body
    );

    foreach ($request->getHeaders() as $key => $value) {
      $laravelRequest->headers->set($key, $value);
    }

    $laravelResponse = app()->handle($laravelRequest);

    $headers = array_merge(
      $laravelResponse->headers->allPreserveCase(),
      $this->buildCookies($laravelResponse->headers->getCookies()),
      [
        #"Access-Control-Allow-Origin" => "http://localhost:8080",
        'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept',
        'Access-Control-Allow-Methods' => 'Access-Control-Allow-Methods',
        'Access-Control-Allow-Credentials' => 'true',
      ]
    );
    $response->writeHead($laravelResponse->getStatusCode(), $headers);
    $response->end($laravelResponse->getContent());

    if (method_exists($kernel, 'terminate')) {
      $kernel->terminate($laravelRequest, $laravelResponse);
    }
  }

  public function handle(\React\Http\Request $request, \React\Http\Response $response)
  {

    $this->post_params = [];

    $request->on('data', function ($body) use ($request, $response) {

      $isJson = function ($string) {
        $result = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) && !empty($result);
      };

      $this->request_body = $body;
      if ($isJson($body)) {
        $this->post_params = json_decode($body, true);
      } else {
        parse_str($body, $this->post_params);
      }
      $this->handleRequest($request, $response);

    });
  }
}
