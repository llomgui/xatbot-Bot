<?php

$onIdle = function () {
    $bot = xatbot\API\ActionAPI::getBot();
    $bot->network->reconnect();
};
