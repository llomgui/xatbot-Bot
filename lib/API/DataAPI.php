<?php

namespace Ocean\Xat\API;

class DataAPI extends BaseAPI
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

    public static function isSetVariable($name)
    {
        return (isset(self::$data[self::getBotID()][$name]));
    }

    public static function unSetVariable($name)
    {
        unset(self::$data[self::getBotID()][$name]);
    }
}
