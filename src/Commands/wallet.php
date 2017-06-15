<?php

$wallet = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'wallet')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $user = $bot->users[$who];

    if (isset($message[1]) || !empty($message[1])) {
        if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
            $user = $bot->users[$message[1]];
        } else {
            foreach ($bot->users as $id => $object) {
                if (is_object($object) && strtolower($object->getRegname()) == strtolower($message[1])) {
                    $user = $object;
                    break;
                }
            }
        }
    } else {
        $user = $bot->users[$who];
    }
    
    if (isset($user)) {
        $display = ($user->isRegistered() ? $user->getRegname() : $user->getID());
        if ($user->hasPower(27) && !($user->getXats() + $user->getDays() == 0)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang(
                    'cmd.wallet',
                    [$display, number_format($user->getXats()), number_format($user->getDays())]
                ),
                $type
            );
        }
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('user.missing.power', [$display, 'show']),
            $type
        );
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.not.here'), $type, true);
    }
};
