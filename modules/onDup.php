<?php

$onDup = function () {

    $bot = actionAPI::getBot();
    stop($bot->data->id);
};
