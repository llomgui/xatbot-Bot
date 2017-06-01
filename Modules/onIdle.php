<?php

$onIdle = function () {
    $bot = OceanProject\Bot\API\ActionAPI::getBot();
    $bot->network->reconnect();
};
