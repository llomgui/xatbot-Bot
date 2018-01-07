<?php

$onDup = function () {

    $bot = xatbot\API\ActionAPI::getBot();
    $bot->stop();
};
