<?php

$hasmost = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'hasmost')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage !hasmost [power]', $type);
    }

    $powers    = xatVariables::getPowers();
    $powerName = strtolower($message[1]);
    $exists    = false;

    foreach ($powers as $id => $array) {
        if ($array['name'] == $powerName || $id == $powerName) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $bot->network->sendMessageAutoDetection($who, 'This power does not exist!', $type);
        return;
    }

    return $bot->network->sendMessageAutoDetection($who, 'https://oceanproject.fr/hasmost/' . $id, $type);
};
