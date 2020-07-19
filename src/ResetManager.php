<?php

namespace Tyea\LaraReactPhp;

use Closure;

class ResetManager
{
	public static $closure = null;

	private function __construct()
	{
	}
	
	public static function get(): ?Closure
	{
		return ResetManager::$closure;
	}
	
	public static function set(?Closure $closure): Void
	{
		ResetManager::$closure = $closure;
	}
	
	public static function execute(): Void
	{
		call_user_func(ResetManager::$closure);
	}
}
