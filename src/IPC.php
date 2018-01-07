<?php

namespace xatbot;

class IPC
{
    private static $socket;
    
    public static function init()
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$socket) {
            return false;
        }
        
        self::$socket = $socket;
        return true;
    }
    
    public static function connect($fileName)
    {
        $fileName = '/opt/xatbot-Bot/sockets/'.$fileName;

        if (!file_exists($fileName)) {
            return false;
        }

        $ret = socket_connect(self::$socket, $fileName);

        if (!$ret) {
            return false;
        }

        socket_set_option(self::$socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec'=> 0]);

        return true;
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
