<?php

$price = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'price')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, "Usage: !price [power]", $type, true);
    }

    $powers = xatVariables::getPowers();

    $match = $bot->network->findPowerMatch($message[1]);

    $powerID = $match[0];

    if (!$powerID) {
        return $bot->network->sendMessageAutoDetection($who, "Power not found.", $type);
    }

    if ($powerID == 95) {
        foreach ($powers as $id => $array) {
            $powers[$powerID]['minCost'] += $array['minCost'];
            $powers[$powerID]['maxCost'] += $array['maxCost'];
        }
    }
    
    if ($powers[$powerID]['minCost'] == 0 || $powers[$powerID]['maxCost'] == 0) {
        return $bot->network->sendMessageAutoDetection($who, "Power has not been priced yet.", $type);
    }
    $dym = $match[1] === false ? 'Did you mean "' . $powers[$powerID]['name'] . '"? ' : '';
    $bot->network->sendMessageAutoDetection($who, $dym . "["  . $powerID . "] " . ucfirst($powers[$powerID]['name']) . ' costs ' . number_format($powers[$powerID]['minCost']) . ' - ' . number_format($powers[$powerID]['maxCost']) . ' xats OR ' . number_format(round($powers[$powerID]['minCost'] / 13.5)) . ' - ' . number_format(round($powers[$powerID]['maxCost'] / 13.5)) . ' days.', $type);
};