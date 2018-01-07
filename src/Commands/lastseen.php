<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$lastseen = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'lastseen')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage !lastseen [regname/xatid]', $type);
    }

    $info = null;
    if (is_numeric($message[1])) {
        $message[1] = (int)$message[1];

        if ($message[1] == 9223372036854775807) {
            return $bot->network->sendMessageAutoDetection($who, 'I am in a 64-bit environment.', $type, true);
        }

        $info = Capsule::table('userinfo')
                ->where('xatid', $message[1])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
    } else {
        $info = Capsule::table('userinfo')
                ->whereRaw('LOWER(regname) = ?', [strtolower($message[1])])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
    }

    if (!empty($info)) {
        $info = $info[0];
        if ($info->optout !== true) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.lastseen.was', [
                    $info->regname,
                    $info->chatname,
                    date_format(new DateTime($info->updated_at), 'l, d M Y H:i:s T')
                ]),
                $type
            );
        } else {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('user.optoutuserinfo', [$info->regname]),
                $type
            );
        }
    } else {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.notindatabase'), $type);
    }
};
