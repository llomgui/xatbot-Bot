<?php
abstract class API
{
	private static $init       = false;

	private static $botID      = 0;
	private static $bot        = 0;
	private static $moduleName = null;

	
	public final static function init()
	{
		if (self::$init) {
			throw new Exception('API already initialized.');
		}

		self::$init           = true;

		$return               = array();
		$return['botID']      = &self::$botID;
		$return['bot']        = &self::$bot;
		$return['moduleName'] = &self::$moduleName;

		return $return;
	}

	public final static function getBotID()
	{
		if (!self::$init) {
			throw new Exception('API not initalized.');
		}

		return self::$botID;
	}
	
	public final static function getBot()
	{
		if (!self::$init) {
			throw new Exception('API not initalized.');
		}

		return self::$bot;
	}
}