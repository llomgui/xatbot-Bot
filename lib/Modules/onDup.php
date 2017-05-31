<?php

$onDup = function () {

    $bot = ActionAPI::getBot();
    stop($bot->data->id);
};
