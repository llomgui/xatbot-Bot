<?php

use xatbot\Models\Log;
use xatbot\API\DataAPI;
use xatbot\Bot\XatVariables;

$onMessage = function (int $who, string $message) {

    if (in_array(substr($message, 0, 2), ['/d', '/m'])) {
        return;
    }

    $bot = xatbot\API\ActionAPI::getBot();

    $regname = $bot->users[$who]->getRegname();

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 1;
    $log->message = '[Main] ' . (!is_null($regname) ? $regname . ' (' . $who . ')' : $who) . ' sent: "' .
        $message . '"';
    $log->save();

    $message = strtolower($message);
    $message2 = explode(' ', $message);

    if (!empty($bot->responses)) {
        $replace = [
            '{name}'    => $bot->users[$who]->getNick(),
            '{status}'  => $bot->users[$who]->getStatus(),
            '{regname}' => $bot->users[$who]->getRegname() ?? $bot->users[$who]->getID(),
            '{users}'   => sizeof($bot->users),
            '{cmdcode}' => $bot->data->customcommand,
            '{id}'      => $bot->users[$who]->getID(),
        ];

        $responses = $bot->responses;
        foreach ($responses as $key => $value) {
            if (strlen($key) == 0) {
                continue;
            }

            if ($key[0] == '*') {
                $key = substr($key, 1);

                if (strtolower($key) == $message) {
                    foreach ($replace as $k => $v) {
                        $value = str_replace(strtolower($k), $v, $value);
                    }
                    
                    return $bot->network->sendMessage($value);
                }
            } else {
                if (sizeof(explode(' ', $key)) > 1) {
                    if (stripos($message, $key) !== false) {
                        foreach ($replace as $k => $v) {
                            $responses = str_replace(strtolower($k), $v, $responses);
                        }

                        return $bot->network->sendMessage($responses[$key]);
                    }
                } else {
                    for ($i = 0; $i < sizeof($message2); $i++) {
                        if ($message2[$i] == strtolower($key)) {
                            foreach ($replace as $k => $v) {
                                $responses = str_replace(strtolower($k), $v, $responses);
                            }

                            return $bot->network->sendMessage($responses[$key]);
                        }
                    }
                }
            }
        }
    }

    return;
};
