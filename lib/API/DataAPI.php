<?php

namespace Ocean\Bot\API;

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

    public static function isSet($name)
    {
        return (isset(self::$data[self::getBotID()][$name]));
    }

    public static function unSet($name)
    {
        unset(self::$data[self::getBotID()][$name]);
    }
}
