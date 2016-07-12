<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class ToDie
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();
        //TODO kill this bot
    }
}
