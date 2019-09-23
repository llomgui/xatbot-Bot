<?php

$latestpower = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'latestpower')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    $lastPowerArray = xatbot\Bot\XatVariables::getLastPower();
    $latestId = $lastPowerArray['id'];
    $power = $lastPowerArray['power'];
    $status = 'UNRELEASED';

    if ($power['isNew']) {
        $status = $power['isLimited'] ? 'LIMITED' : 'UNLIMITED';
    }

    $implode = [
        ucfirst($power['name']) . '(ID: '. $latestId . ')',
        'Pawns: ' . (isset($power['pawns']) ? implode(', ', array_unique($power['pawns'])) : 'none'),
        'Smilies: ' . implode(', ', $power['smilies']),
        'Store price: ' . (isset($power['storeCost']) ? $power['storeCost'] . ' xats' : 'Not yet priced'),
        'Status: ' . $status
    ];

    $bot->network->sendMessageAutoDetection($who, implode(' | ', $implode), $type);
};
