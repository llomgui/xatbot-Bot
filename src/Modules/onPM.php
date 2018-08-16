<?php

use xatbot\Models\Log;

$onPM = function (int $who, string $message) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!isset($bot->users[$who])) {
        return;
    }

    $regname = $bot->users[$who]->getRegname();

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 2;
    $user = (!is_null($regname) ? $regname . ' (' . $who . ')' : $who);
    $log->message = '[PM] ' . $user . ' sent: "' . utf8_encode($message) . '"';
    $log->save();

    if (empty($message)) {
        return;
    }

    if (!empty($bot->snitchlist)) {
        foreach ($bot->snitchlist as $snitch) {
            if (isset($bot->users[$snitch['xatid']])) {
                $bot->network->sendPrivateConversation($snitch['xatid'], 'PM - [' . $who . '] ' . $message);
            }
        }
    }
};
