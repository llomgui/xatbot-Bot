<?php

use OceanProject\Bot\XatVariables;

$onLoginInfo = function (array $array) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (isset($array['RL']) && $array['RL'] == '1') {
        XatVariables::setLoginPacket($array);
        XatVariables::setLoginTime(time());
        $bot->network->reconnect();
    }
};
