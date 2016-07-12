<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\Variables;

class RandomSmiley
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (empty($message[1]) || !isset($message[1])) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Usage: !randomsmiley [1 - 20] [optional power]',
                $type,
                true
            );
        }

        if (!is_numeric($message[1]) || $message[1] > 25 || $message[1] < 1) {
            return $bot->network->sendMessageAutoDetection($who, 'Must be 1 - 25.', $type, true);
        }

        $powers = Variables::getPowers();
        $exist  = false;

        $rand = [];

        if (!empty($message[2]) || isset($message[2])) {
            $rand = array_fill(0, $message[1] - 1, 'random');
            foreach ($powers as $id => $array) {
                if ($array['name'] == strtolower($message[2]) || $id == $message[2]) {
                    $exist = true;
                    break;
                }
            }

            if (!$exist) {
                return $bot->network->sendMessageAutoDetection($who, 'This power does not exist!', $type);
            }
            /*
                TODO
                check if power is enabled
            */

            $rand[] = $array['name'];
        } else {
            $rand = array_fill(0, $message[1], 'random');
        }

        shuffle($rand);
        $bot->network->sendMessageAutoDetection(
            $who,
            'Randomly generated Smiley: (' . implode('#', $rand) . ')',
            $type
        );
    }
}
