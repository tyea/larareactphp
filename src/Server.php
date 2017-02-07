<?php

namespace LaravelReactPHP;

class Server
{

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var int
     */
    protected $verbose;

    /**
     *
     *
     * @param string $host binding host
     * @param int $port binding port
     * @param int $verbose log
     */
    public function __construct($host, $port, $verbose)
    {
        $this->host = $host;
        $this->port = $port;
        $this->verbose = $verbose;
    }

    /**
     * Running HTTP Server
     */
    public function run()
    {
        $loop = new \React\EventLoop\StreamSelectLoop();
        $socket = new \React\Socket\Server($loop);
        $http = new \React\Http\Server($socket, $loop);
        $http->on('request', function ($request, $response) {
            with(new HttpSession($this->host, $this->port, $this->verbose))->handle($request, $response);
        });
        $socket->listen($this->port, $this->host);
        $loop->run();
    }
}
