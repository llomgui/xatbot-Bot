<?php

$onRedirect = function (array $array) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
