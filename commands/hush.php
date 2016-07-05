<?php

$hush = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1]) || !isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
        if ($type == 1) {
            $type = 2;
        }

        return $bot->network->sendMessageAutoDetection($who, 'Usage: !hush [guest/member/mod/owner] [seconds] [reason]', $type);
    }

    $rank    = $message[1];
    $seconds = $message[2];
    
    if (isset($message[3])) {
        unset($message[0]);
        unset($message[1]);
        unset($message[2]);

        $reason = implode(' ', $message);
    }
    
    switch ($rank) {
        case 'guest':
            $rank = 'g';
            break;
        case 'member':
            $rank = 'm';
            break;
        case 'mod':
            $rank = 'd';
            break;
        case 'owner':
            $rank = 'o';
            break;
        default:
            return $bot->network->sendMessageAutoDetection($who, 'That\'s not a valid rank.', $type);
    }
    $bot->network->sendMessage('/h' . $rank . $seconds . ' ' . $reason);
};
