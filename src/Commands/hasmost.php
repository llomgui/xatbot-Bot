<?php

use xatbot\Bot\XatVariables;

$hasmost = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'hasmost')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage !hasmost [power]', $type);
    }

    $powers    = XatVariables::getPowers();
    $powerName = strtolower($message[1]);
    $exists    = false;

    foreach ($powers as $id => $array) {
        if ($array['name'] == $powerName || $id == $powerName) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.powernotexist'), $type);
        return;
    }

    return $bot->network->sendMessageAutoDetection(
        $who,
        XatVariables::getConfig()['website_url'] . '/panel/hasmost/' . $id,
        $type
    );
};
