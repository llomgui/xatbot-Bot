<?php

$users = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'users')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $ucount = count($bot->users);

    if ($ucount <= 0) {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.users.nobodyhere'), $type);
    } else {
        $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.users.count', [
                $ucount,
                ($ucount > 1 ? 's' : '')
            ]),
            $type
        );
    }
};
