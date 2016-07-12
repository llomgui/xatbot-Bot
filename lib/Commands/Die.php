<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class die
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();
        //TODO kill this bot
    }
}
