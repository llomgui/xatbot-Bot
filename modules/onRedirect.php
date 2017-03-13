<?php

$onRedirect = function (array $array) {

    $bot = actionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
