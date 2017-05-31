<?php

$onDup = function () {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();
    stop($bot->data->id);
};
