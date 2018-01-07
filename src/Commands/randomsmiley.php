<?php

$randomsmiley = function (int $who, array $message, int $type) {
    
    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'randomsmiley')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }
    
    if (!$bot->botHasPower(272)) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('missing.power', ['random']), $type);
    }
    
    if (empty($message[1]) || !isset($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !randomsmiley [1 - 20] [optional power]',
            $type,
            true
        );
    }

    if (!is_numeric($message[1]) || $message[1] > 25 || $message[1] < 1) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.randomsmiley.mustbe'), $type, true);
    }

    $powers = OceanProject\Bot\XatVariables::getPowers();
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
            return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
        }

        if (!$bot->botHasPower($array['name'])) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('missing.power', [$array['name']]),
                $type
            );
        }
        $rand[] = $array['name'];
    } else {
        $rand = array_fill(0, $message[1], 'random');
    }

    shuffle($rand);
    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.randomsmiley.generated', [implode('#', $rand)]),
        $type
    );
};
