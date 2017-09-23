<?php

$onDone = function (array $array) {

    $bot = OceanProject\API\ActionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;

    if ($bot->data->was_connected == false) {
        $bot->network->sendMessage(
            '/g'
        );

        $bot->data->was_connected = true;
        $bot->data->save();
    }
};
