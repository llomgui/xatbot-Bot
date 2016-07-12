<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class GameBanMe
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (!isset($message[1]) ||
            empty($message[1]) ||
            !isset($message[2]) ||
            empty($message[2]) ||
            !is_numeric($message[2])
        ) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !gamebanme [snake/space/match/maze/code/slot] [hours]',
                $type,
                true
            );
        }

        $gameban = $message[1];
        $hours   = $message[2];

        switch (strtolower($gameban)) {
            case 'snake':
            case 'snakeban':
                $gamebanid = 134;
                break;

            case 'space':
            case 'spaceban':
                $gamebanid = 136;
                break;

            case 'match':
            case 'matchban':
                $gamebanid = 140;
                break;

            case 'maze':
            case 'mazeban':
                $gamebanid = 152;
                break;

            case 'code':
            case 'codeban':
                $gamebanid = 162;
                break;

            case 'slot':
            case 'slotban':
                $gamebanid = 236;
                break;

            default:
                return $bot->network->sendMessageAutoDetection($who, 'That\'s not a valid gameban', $type);
                break;
        }

        if (!$bot->botHasPower($gamebanid)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                sprintf('Sorry, but i don\'t have the power \'%s\'.', strtolower($gameban)),
                $type
            );
        }

        $bot->network->ban($who, $hours, 'Requested', 'g', $gamebanid);
    }
}
