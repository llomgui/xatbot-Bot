<?php

namespace OceanProject;

use OceanProject\IPC;

class Userinfo
{
    private static $userinfo;

    public static function init()
    {
        self::$userinfo = [];
    }

    public static function getUserinfo()
    {
        return self::$userinfo;
    }

    public static function setUserinfo($array)
    {
        self::$userinfo = $array;
        if (sizeof(self::$userinfo) > 50) {
            IPC::init();
            IPC::connect('userinfo.sock');
            IPC::write(serialize(self::$userinfo));
            IPC::close();
            self::$userinfo = [];
        }
    }
}
