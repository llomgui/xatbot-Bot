<?php

$onAbort = function () {

    $bot = actionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
