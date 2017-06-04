<?php

$onIdle = function () {
    $bot = OceanProject\API\ActionAPI::getBot();
    $bot->network->reconnect();
};
