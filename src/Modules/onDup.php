<?php

$onDup = function () {

    $bot = OceanProject\API\ActionAPI::getBot();
    $bot->stop();
};
