<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class GuestSelf
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!$bot->botHasPower(32)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                sprintf('Sorry, but i don\'t have the power \'%s\'.', 'guestself'),
                $type
            );
        }

        $bot->network->sendMessage('/g');
    }
}
