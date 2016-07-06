<?php

$onIdle = function () {
    $bot = actionAPI::getBot();

    $bot->network->reconnect();
};
