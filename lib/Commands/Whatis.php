<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;
use Ocean\Xat\Variables;

class Whatis
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        if (empty($message[1]) || !isset($message[1])) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !whatis [smiley]', $type, true);
        }

        $powers = Variables::getPowers();

        foreach ($powers as $power) {
            if ($power['name'] == strtolower($message[1])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    ucfirst($power['name']) . ' is a power, not a smiley.',
                    $type
                );
            }
            foreach ($power['smilies'] as $smiley) {
                if ($smiley == strtolower($message[1])) {
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        '('. $smiley . ') is from the power (' . $power['name'] . ')',
                        $type
                    );
                }
            }
        }

        /*

        TODO Get a list of free smilies and search it too.

        */

        $bot->network->sendMessageAutoDetection($who, '"' . $message[1] . '" was not found as a smiley.', $type);
    }
}
