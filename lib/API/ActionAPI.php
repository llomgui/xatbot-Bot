<?php

namespace Ocean\Bot\API;

class ActionAPI extends BaseAPI
{
    public static function __callStatic($function, $arguments)
    {
        $callback    = [];
        $callback[0] = self::getBot();
        $callback[1] = $function;

        call_user_func_array($callback, $arguments);
    }
}
