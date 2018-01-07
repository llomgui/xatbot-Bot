<?php

$price = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'price')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !price [power]', $type, true);
    }

    $powers = xatbot\Bot\XatVariables::getPowers();

    if (isset($message[2])) {
        $minCost = $maxCost = 0;
        for ($i = 1; $i < sizeof($message); $i++) {
            $match = $bot->network->findPowerMatch($message[$i]);
            if (isset($match[0])) {
                $powerID = $match[0];
                $minCost += $powers[$powerID]['minCost'];
                $maxCost += $powers[$powerID]['maxCost'];
            }
        }
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.price.thosepowers', [
                number_format($minCost),
                number_format($maxCost),
                number_format(round($minCost / 13.5)),
                number_format(round($maxCost / 13.5))
            ]),
            $type
        );
    }

    if (strtolower($message[1]) == 'latest') {
        $message[1] = end($powers)['name'];
    }

    $match = $bot->network->findPowerMatch($message[1]);

    $powerID = $match[0];

    if (!$powerID) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
    }

    if ($powerID == 95) {
        foreach ($powers as $id => $array) {
            $powers[$powerID]['minCost'] += $array['minCost'];
            $powers[$powerID]['maxCost'] += $array['maxCost'];
        }
    }
    
    if ($powers[$powerID]['minCost'] == 0 || $powers[$powerID]['maxCost'] == 0) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.price.notpriced', [
                $powerID,
                ucfirst($powers[$powerID]['name'])
            ]),
            $type
        );
    }
    $dym = $match[1] === false ? $bot->botlang('cmd.didyoumean', [ucfirst($powers[$powerID]['name'])]) : '';
    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.price.result', [
            $dym,
            $powerID,
            ucfirst($powers[$powerID]['name']),
            number_format($powers[$powerID]['minCost']),
            number_format($powers[$powerID]['maxCost']),
            number_format(round($powers[$powerID]['minCost'] / 13.5)),
            number_format(round($powers[$powerID]['maxCost'] / 13.5))
        ]),
        $type
    );
};
