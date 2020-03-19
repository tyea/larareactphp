<?php

namespace Tyea\LaraReactPhp;

use React\EventLoop\Factory as EventLoopFactory;
use React\Http\Server as HttpServer;
use React\Socket\Server as Socket;
use Exception;

class ReactPhpHttpServer
{
	private $eventLoop = null;
	private $httpServer = null;
	private $socket = null;

	public function __construct(String $uri)
	{
		$this->eventLoop = EventLoopFactory::create();
		$this->httpServer = new HttpServer(["Tyea\\LaraReactPhp\\Handler", "handle"]);
		$this->socket = new Socket($uri, $this->eventLoop);
	}
	
	public function run(): Void
	{
		$this->httpServer->listen($this->socket);
		$this->httpServer->on("error", ["Tyea\\LaraReactPhp\\Handler", "recover"]);
		$this->eventLoop->run();
	}
}
