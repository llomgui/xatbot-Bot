<?php

$onLoginInfo = function ($array) {

    $bot = actionAPI::getBot();

    if (isset($array['RL']) && $array['RL'] == '1') {
        xatVariables::setLoginPacket($array);
        xatVariables::setLoginTime(time());
        $bot->network->reconnect();
    }

};
