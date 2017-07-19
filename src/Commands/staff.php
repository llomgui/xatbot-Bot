<?php

$staff = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'staff')) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('not.enough.rank'),
            $type
        );
    }

    if (empty($message[1]) || empty($message[2]) || empty($message[3])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !staff [add/remove] [xatid/regname] [rank]',
            $type
        );
    }

    switch (strtolower($message[1])) {
        case 'add':
            break;

        case 'remove':
            break;

        default:
            $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !staff [add/remove] [xatid/regname] [rank]',
                $type
            );
            break;
    }
};
