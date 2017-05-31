<?php

$onDone = function (array $array) {

    $bot = ActionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;
};
