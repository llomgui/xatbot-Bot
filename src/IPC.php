<?php

namespace OceanProject;

class IPC
{
    private static $socket;
    
    public static function init()
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$socket) {
            return $socket;
        }
        
        self::$socket = $socket;
    }
    
    public static function connect($fileName)
    {
        $fileName = '/opt/OceanProject-Bot/sockets/'.$fileName;

        if (!file_exists($fileName)) {
            return -1;
        }

        $ret = socket_connect(self::$socket, $fileName);

        if (!$ret) {
            return;
        }
    }
    
    public static function read($size)
    {
        return socket_read(self::$socket, $size);
    }
    
    public static function write($packet)
    {
        return socket_write(self::$socket, $packet);
    }
    
    public static function close()
    {
        return socket_close(self::$socket);
    }
}
