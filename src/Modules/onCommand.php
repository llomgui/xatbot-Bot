<?php

use xatbot\Models\Log;
use xatbot\API\DataAPI;

$onCommand = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!isset($bot->users[$who])) {
        return;
    }

    if (DataAPI::isSetVariable('userCooldown_' . $who)) {
        $cooldown = DataAPI::get('userCooldown_' . $who);
        if ($cooldown['lastCommandSent'] + 10 > time()) {
            $cooldown['commandCount'] += 1;
        } else {
            $cooldown['commandCount'] = 0;
        }
        $cooldown['lastCommandSent'] = time();
        DataAPI::set('userCooldown_' . $who, $cooldown);
    }

    $message = implode(' ', $message);

    if (strpos($message, 'getmain') === false) {
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

        $log->message .= (!is_null($regname) ? $regname . ' (' . $who . ')' : $who) . ' sent: "' .
            utf8_encode($message) . '"';
        $log->save();

        if (DataAPI::isSetVariable('userEvent_' . $who)) {
            $event = DataAPI::get('userEvent_' . $who);
            $event['amount_commands'] += 1;
            DataAPI::set('userEvent_' . $who, $event);
        }
    }
};
