<?php

$slots = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'slots')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $smilies = [
        '(smile#)', '(biggrin#)', '(wink#)', '(eek#)', '(tongue#)', '(cool#)',
        '(mad#)', '(confused#)', '(redface#)', '(frown#)', '(crying#)'
    ];

    if ($bot->botHasPower(236)) {
        $smilies = ['(slotban#)', '(slotbar#)', '(cherries#)', '(orange2#)', '(plum2#)', '(seven#)'];
    }

    $smilieCount = 3;
    if (isset($message[1]) && !empty($message[1])) {
        $difficulty = ['easy' => 2, 'hard' => 4, 'expert' => 5, 'extreme' => 6, 'impossible' => 7, 'gg' => 10];
        if (array_key_exists(strtolower($message[1]), $difficulty)) {
            $smilieCount = $difficulty[strtolower($message[1])];
        }
    }

    $spun = [];

    for ($i = 1; $i <= $smilieCount; $i++) {
        array_push($spun, $smilies[array_rand($smilies)]);
    }

    $user = $bot->users[$who];
    $response = isset($user) ?
        ($user->isRegistered() ? $user->getRegname() : $user->getID()) . " has spun: " :
        $who . " has spun: ";

    $bot->network->sendMessageAutoDetection(
        $who,
        'Spinning: ' . implode('|', array_fill(0, $smilieCount, '(rolling#)')),
        $type
    );

    if (count(array_unique($spun)) == 1) {
        $newMessage = $bot->botlang('cmd.slots.won', [$response, implode('|', $spun)]);
    } else {
        $newMessage = $bot->botlang('cmd.slots.lost', [$response, implode('|', $spun)]);
    }

    if (sizeof($bot->packetsinqueue) > 0) {
        $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 1000] = [
            'who' => $who,
            'message' => $newMessage,
            'type' => $type
        ];
    } else {
        $bot->packetsinqueue[round(microtime(true) * 1000) + 1000] = [
            'who' => $who,
            'message' => $newMessage,
            'type' => $type
        ];
    }
};
