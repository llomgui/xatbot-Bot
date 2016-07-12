<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Hush
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!$bot->botHasPower(51)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                sprintf('Sorry, but i don\'t have the power \'%s\'.', 'hush'),
                $type
            );
        }

        if (!isset($message[1]) ||
            empty($message[1]) ||
            !isset($message[2]) ||
            empty($message[2]) ||
            !is_numeric($message[2])
        ) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !hush [guest/member/mod/owner] [seconds] [reason]',
                $type,
                true
            );
        }

        $rank    = $message[1];
        $seconds = $message[2];

        if (isset($message[3])) {
            $reason = implode(' ', array_slice($message, 3));
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
    }
}
