<?php

$latestpower = function ($who, $message, $type) {

    $bot = actionAPI::getBot();

    $pow2 = json_decode(file_get_contents('http://xat.com/web_gear/chat/pow2.php'), true);
    $powers = json_decode(file_get_contents('http://xat.com/json/powers.php'), true);

    if (!$pow2) {
        return $bot->network->sendMessageAutoDetection($who, 'Could not access pow2 at this moment.', $type);
    }

    $latestID = $pow2[0][1]['id'];

    $latestName = "";
    foreach (array_reverse($pow2[6][1]) as $powerName => $powerID) { //Technically it's a possibility that it MIGHT not be at the very end of pssa.
        if ($powerID == $latestID) {
            $latestName = $powerName;
            break;
        }
    }

    $pawns = '| Pawns: ';
    foreach ($pow2[7][1] as $hatCode => $pawnInfo) {
        if ($hatCode == 'time') {
            continue;
        }

        if ($pawnInfo[0] == $latestID) {
            $pawns .= 'h' . $hatCode . ', ';
        }
    }

    $smilies = '| Smilies: ';
    foreach ($pow2[6][1] as $smileyName => $powerID) {
        if ($powerID == $latestID) {
            $smilies .= $smileyName . ', ';
        }
    }

    if (isset($powers[$latestID])) {
        $storePrice = isset($powers[$latestID]['x']) ? $powers[$latestID]['x'].  ' xats' : $powers[$latestID]['d'] . ' days';
    }

    $bot->network->sendMessageAutoDetection($who, ucfirst($latestName) . ' (ID: '. $latestID . ') ' . rtrim($pawns, ', ') . ' ' . rtrim($smilies, ', ') . ' | Store price: ' . (isset($storePrice) ? $storePrice : "Unknown"), $type);
};
