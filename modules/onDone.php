<?php

$onDone = function ($array) {

    $bot = actionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;
};
