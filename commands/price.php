<?php

$price = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, "Usage: !price [power]", $type, true);
    }

    $powers = xatVariables::getPowers();

    if (!is_numeric($message[1])) {
        foreach ($powers as $powerID => $powerValues) {
            if ($powerValues['name'] == strtolower($message[1])) {
                $message[1] = $powerID;
                break;
            }
        }
    }

    $powerID = $message[1];

    if (!isset($powerID)) {
        return $bot->network->sendMessageAutoDetection($who, "Power not found.", $type);
    }

    if ($powers[$powerID]['minCost'] == 0 || $powers[$powerID]['maxCost'] == 0) {
        return $bot->network->sendMessageAutoDetection($who, "Power has not been priced yet.", $type);
    }

    $bot->network->sendMessageAutoDetection($who, "["  . $powerID . "] " . ucfirst($powers[$powerID]['name']) . ' costs ' . number_format($powers[$powerID]['minCost']) . ' - ' . number_format($powers[$powerID]['maxCost']) . ' xats OR ' . number_format(round($powers[$powerID]['minCost'] / 13.5)) . ' - ' . number_format(round($powers[$powerID]['maxCost'] / 13.5)) . ' days.', $type);
};
