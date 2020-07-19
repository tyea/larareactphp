<?php

namespace Tyea\LaraReactPhp\Servers;

use React\EventLoop\Factory as EventLoopFactory;
use React\Http\Server as HttpServer;
use React\Socket\Server as Socket;
use Exception;

class ReactPhpHttpServer
{
	private function __construct()
	{
	}

	public static function run(String $uri)
	{
		$eventLoop = EventLoopFactory::create();
		$httpServer = new HttpServer(["Tyea\\LaraReactPhp\\Handlers\\HttpServerHandler", "handle"]);
		$socket = new Socket($uri, $eventLoop);
		$httpServer->listen($socket);
		$eventLoop->run();
	}
}
