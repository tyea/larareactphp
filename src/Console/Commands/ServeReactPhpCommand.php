<?php

namespace Tyea\LaraReactPhp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Str;
use Tyea\LaraReactPhp\ReactPhpHttpServer;

class ServeReactPhpCommand extends Command
{
    protected $signature = "serve:reactphp {host=127.0.0.1} {port=8000}";
    protected $description = "Serve the application using ReactPHP";

    public function handle(): Int
    {
    	$host = $this->argument("host");
    	$port = $this->argument("port");
		$validator = Validator::make(
			["host" => $host, "port" => $port],
			["host" => "required|string|ipv4", "port" => "required|integer|between:1,65535"]
		);
		// @todo error formatting
    	$validator->validate();
    	$uri = $host . ":" . $port;
        $this->line("<info>Laravel ReactPHP server started:</info> http://" . $uri);
		// @todo hot reloading
        $server = new ReactPhpHttpServer($uri);
        $server->run();
        return 0;
    }
}
