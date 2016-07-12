<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class RandomUser
{
    public function __invoke($who, $message, $type)
    {
        $bot    = ActionAPI::getBot();
        $random = array_rand($bot->users);
        $bot->network->sendMessageAutoDetection($who, $bot->users[$random]->getNick(), $type);
    }
}
