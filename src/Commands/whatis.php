<?php

$whatis = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'whatis')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !whatis [smiley]', $type, true);
    }
    
    $powers = OceanProject\Bot\XatVariables::getPowers();
    
    foreach ($powers as $power) {
        if ($power['name'] == strtolower($message[1])) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.whatis.notasmiley', [ucfirst($power['name'])]),
                $type
            );
        }
        foreach ($power['smilies'] as $smiley) {
            if ($smiley == strtolower($message[1])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.whatis.message', [
                        $smiley,
                        $power['name']
                    ]),
                    $type
                );
            }
        }
    }
    
    if (in_array($message[1], OceanProject\Bot\XatVariables::getFreeSmilies())) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.whatis.isfreesmiley', [$message[1]]),
            $type
        );
    }
    
    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.whatis.notfound', [$message[1]]), $type);
};
