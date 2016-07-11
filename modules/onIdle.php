<?php

use Ocean\Bot\API\ActionAPI;

$onIdle = function () {
    $bot = ActionAPI::getBot();

    $bot->network->reconnect();
};
