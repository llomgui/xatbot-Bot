<?php

namespace Ocean\Xat\Modules;

use Ocean\Xat\API\ActionAPI;

class OnIdle
{
    public function __invoke()
    {
        $bot = ActionAPI::getBot();
        $bot->network->reconnect();
    }
}
