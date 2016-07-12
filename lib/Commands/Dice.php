<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Dice
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();
        $bot->network->sendMessageAutoDetection($who, 'I rolled ' . rand(1, 6), $type);
    }
}
