<?php

use OceanProject\API\DataAPI;
use OceanProject\Models\Log;

$onPC = function (int $who, string $message) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!isset($bot->users[$who])) {
        return;
    }

    $regname = $bot->users[$who]->getRegname();

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 3;
    $log->message = '[PC] ' . (!is_null($regname) ? $regname . ' (' . $who . ')' : $who) . ' sent: "' . $message . '"';
    $log->save();

    $message = explode(' ', $message);

    if (!isset($message[0])) {
        return;
    }

    if (!empty($bot->snitchlist)) {
        foreach($bot->snitchlist as $snitch) {
            if (isset($bot->users[$snitch['xatid']])) {
                $bot->network->sendPrivateConversation($snitch['xatid'], 'PC - [' . $who . '] ' . implode($message));
            }
        }
    }

    if ($bot->data->automember == 'maths' && DataAPI::isSetVariable('automember_' . $who)) {
        if (DataAPI::get('automember_' . $who) == $message[0]) {
            $bot->network->sendPrivateConversation($who, 'You are now a member');
            DataAPI::unSetVariable('automember_' . $who);
            $bot->network->ChangeRank($who, 'member');
        } else {
            $bot->network->sendPrivateConversation($who, 'Wrong answer!');
        }
    }

    return;
};
