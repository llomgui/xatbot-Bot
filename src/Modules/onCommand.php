<?php

use OceanProject\Models\Log;

$onCommand = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!isset($bot->users[$who])) {
        return;
    }

    $regname = $bot->users[$who]->getRegname();

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 5;

    if ($type == 1) {
        $log->message = '[Main] ';
    } elseif ($type == 2) {
        $log->message = '[PM] ';
    } elseif ($type == 3) {
        $log->message = '[PC] ';
    }

    $log->message .= (!is_null($regname) ? $regname . ' (' . $who . ')' : $who) . ' sent: "' . $message . '"';
    $log->save();
};
