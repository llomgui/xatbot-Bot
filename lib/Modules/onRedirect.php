<?php

$onRedirect = function (array $array) {

    $bot = ActionAPI::getBot();
    // for the time being
    $bot->network->reconnect();
};
