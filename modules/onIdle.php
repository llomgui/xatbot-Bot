<?php

use Ocean\Xat\API\ActionAPI;

$onIdle = function () {
    $bot = ActionAPI::getBot();

    $bot->network->reconnect();
};
