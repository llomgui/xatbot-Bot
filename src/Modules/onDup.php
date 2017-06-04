<?php

$onDup = function () {

    $bot = OceanProject\API\ActionAPI::getBot();
    stop($bot->data->id);
};
