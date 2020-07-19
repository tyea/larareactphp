<?php

namespace Tyea\LaraReactPhp\Handlers;

use Closure;

class StateHandler
{
	private static $closure = null;

	private function __construct()
	{
	}

	public static function get(): ?Closure
	{
		return StateHandler::$closure;
	}

	public static function set(?Closure $closure): Void
	{
		StateHandler::$closure = $closure;
	}

	public static function handle(): Void
	{
		call_user_func(StateHandler::$closure);
	}
}
