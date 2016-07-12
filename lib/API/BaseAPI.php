<?php

namespace Ocean\Xat\API;

abstract class BaseAPI
{
    private static $init       = false;

    private static $botID      = 0;
    private static $bot        = 0;
    private static $moduleName = null;


    final public static function init()
    {
        if (self::$init) {
            throw new \Exception('API already initialized.');
        }

        self::$init           = true;

        $return               = [];
        $return['botID']      = &self::$botID;
        $return['bot']        = &self::$bot;
        $return['moduleName'] = &self::$moduleName;

        return $return;
    }

    final public static function getBotID()
    {
        if (!self::$init) {
            throw new \Exception('API not initalized.');
        }

        return self::$botID;
    }

    final public static function getBot()
    {
        if (!self::$init) {
            throw new \Exception('API not initalized.');
        }
        return self::$bot;
    }
}
