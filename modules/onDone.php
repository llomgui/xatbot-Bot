<?php

$onDone = function (array $array) {

    $bot = actionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;
};
