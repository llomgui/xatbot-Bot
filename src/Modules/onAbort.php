<?php

$onAbort = function (array $array) {

    $bot = OceanProject\API\ActionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
