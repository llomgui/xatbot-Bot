<?php

use xatbot\Bot\XatVariables;

$onLoginInfo = function (array $array) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (isset($array['RL']) && $array['RL'] == '1') {
        XatVariables::setLoginPacket($array);
        XatVariables::setLoginTime(time());
        $bot->network->reconnect();
    }
};
