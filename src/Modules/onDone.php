<?php

$onDone = function (array $array) {

    $bot = OceanProject\API\ActionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;
};
