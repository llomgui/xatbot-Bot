<?php

$onRedirect = function (array $array) {

    $bot = xatbot\API\ActionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
