<?php

$onIdle = function () {
    $bot = ActionAPI::getBot();

    $bot->network->reconnect();
};
