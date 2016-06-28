<?php
require_once 'base.php';

class dataAPI extends API
{
	private static $data;

	public static function dumpVars()
	{
		var_dump(self::$data);
	}

	//::////////////////////////////////////////////////////////////////////////
	//::// Overloading methods
	//::////////////////////////////////////////////////////////////////////////

	public static function set($name, $value)
	{
		self::$data[self::getBotID()][$name] = $value;
	}

	public static function get($name)
	{
		return self::$data[self::getBotID()][$name];
	}

	public static function is_set($name)
	{
		return ( isset(self::$data[self::getBotID()][$name]) );
	}

	public static function un_set($name)
	{
		unset(self::$data[self::getBotID()][$name]);
	}
}
