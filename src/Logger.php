<?php

namespace OceanProject;

use Monolog\Logger as mLogger;
use Monolog\ErrorHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class Logger
{
    private static $logger;

    public static function init($server)
    {
        $format = "[%datetime%] %channel% %level_name%: %message%\n";
        $formatter = new LineFormatter($format);

        $stream = new StreamHandler('logs/' . $server . '.log');
        $stream->setFormatter($formatter);

        self::$logger = new mLogger($server);
        ErrorHandler::register(self::$logger);
        self::$logger->pushHandler($stream);
    }

    public static function getLogger()
    {
        return self::$logger;
    }
}
