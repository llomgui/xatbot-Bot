<?php

$onUserLeave = function (int $who) {

    $bot  = actionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    unset($bot->users[$who]);

    if (dataAPI::is_set('away_' . $who)) {
        dataAPI::un_set('away_' . $who);
    }

    dataAPI::set('left_' . $who, time());

    return;
};
