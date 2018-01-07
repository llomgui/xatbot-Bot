<?php

$onDone = function (array $array) {

    $bot = xatbot\API\ActionAPI::getBot();
    $bot->network->idleTime = time();
    $bot->done = true;

    if ($bot->data->was_connected == false) {
        $bot->network->sendMessage(
            '/g'
        );

        $bot->data->was_connected = true;
        $bot->data->save();
    }

    if ($bot->refreshing === true) {
        $bot->network->sendMessage('I am ready to serve!');
    }
};
