<?php

$onRedirect = function ($array) {

    $bot = actionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
