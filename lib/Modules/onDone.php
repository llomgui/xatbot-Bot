<?php

$onDone = function (array $array) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;
};
