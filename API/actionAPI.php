<?php
require_once 'base.php';

class ActionAPI extends API
{
    public static function __callStatic($function, $arguments)
    {
        $callback    = [];
        $callback[0] = self::getBot();
        $callback[1] = $function;

        call_user_func_array($callback, $arguments);
    }
}
