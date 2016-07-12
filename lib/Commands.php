<?php

namespace Ocean\Xat;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Ocean\Xat\API\ActionAPI;

class Commands implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container["commands"] = function () use ($container) {
            $commands = new Container();

            $commands["say"] = function () use ($container) {
                return new Commands\Say();
            };

            return $commands;
        };
    }
}