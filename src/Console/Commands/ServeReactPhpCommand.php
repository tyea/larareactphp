<?php

namespace Tyea\LaraReactPhp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Tyea\LaraReactPhp\ReactPhpHttpServer;
use Symfony\Component\Console\Exception\InvalidArgumentException;

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
		if ($validator->fails()) {
			$argument = array_key_exists("host", $validator->errors()->messages()) ? "host" : "port";
			throw new InvalidArgumentException("The \"" . $argument . "\" argument is not valid.");
		}
		Config::set("filesystems.disks.reactphp", [
            "driver" => "local",
            "root" => realpath(Config::get("filesystems.disks.local.root") . "/../../public"),
        ]);
    	$uri = $host . ":" . $port;
        $this->line("<info>Laravel ReactPHP server started:</info> http://" . $uri);
        ReactPhpHttpServer::run($uri);
        return 0;
    }
}
