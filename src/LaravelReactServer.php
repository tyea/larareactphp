<?php

namespace LaravelReactPHP;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;
use React\Promise\Promise;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

class LaravelReactServer
{
    /**
     * @var \React\EventLoop\LoopInterface|null
     */
    protected $loop = null;

    /**
     * @var \React\Socket\Server|null
     */
    protected $socket = null;

    /**
     * @var \React\Http\Server|null
     */
    protected $server = null;

    /**
     * LaravelReactServer constructor.
     *
     * @param string $listen
     * @param int $verbose
     */
    public function __construct($listen, $verbose)
    {
        $this->verbose = (int)$verbose;

        $this->loop = \React\EventLoop\Factory::create();

        $this->socket = new \React\Socket\Server($listen, $this->loop);

        $this->server = new \React\Http\Server(
            $this->makeRequestCallback()
        );
    }

    /**
     * Running HTTP Server
     */
    public function run()
    {
        $this->server->listen($this->socket);

        $this->loop->run();
    }

    /**
     * @return \Closure
     */
    public function makeRequestCallback()
    {
        return function (ServerRequestInterface $request) {
            $request->getBody()->on('data', function ($data) use (&$contentLength, &$body) {
                $body .= $data;
            });
            return new Promise(function ($resolve, $reject) use ($request, &$body) {
                $request->getBody()->on('end', function () use ($resolve, &$contentLength, $request, &$body){
                    $request2 = $request->withParsedBody(json_decode($body, true));

                    $symfonyRequest = with(new HttpFoundationFactory)->createRequest($request2);

                    $symfonyResponse = app()->handle($symfonyRequest);

                    /** @var \Psr\Http\Message\ResponseInterface $psr7response */
                    $psr7response = with(new DiactorosFactory)->createResponse($symfonyResponse);

                    $response = new Response(
                        $psr7response->getStatusCode(),
                        $psr7response->getHeaders(),
                        $psr7response->getBody(),
                        $psr7response->getProtocolVersion(),
                        $psr7response->getReasonPhrase()
                    );

                    $resolve($response);
                });
            });
        };
    }
}
