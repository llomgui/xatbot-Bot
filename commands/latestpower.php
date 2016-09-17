<?php

$latestpower = function ($who, $message, $type) {

    /* Pow2 Indexs
        0 = last
        1 = backs
        2 = actions
        3 = hugs
        4 = topsh (smilies)
        5 = isgrp
        6 = pssa (powernames)
        7 = pawns
        8 = nomob (temp)
        9 = pawns2 (temp)
    */	

    $bot = actionAPI::getBot();

    $stream = stream_context_create(['http'=> ['timeout' => 1]]);
    $pow2 = json_decode(file_get_contents('http://xat.com/web_gear/chat/pow2.php?' . time(), false, $stream), true);
    $powers = json_decode(file_get_contents('http://xat.com/json/powers.php?' . time(), false, $stream), true);

    if (!$pow2 || !$powers) {
        return $bot->network->sendMessageAutoDetection($who, 'Could not access xat\'s json files at this moment.', $type);
    } 

    $latestID = end($pow2[6][1]) >= $pow2[0][1]['id'] ? end($pow2[6][1]):$pow2[0][1]['id']; //check pssa
    $latestID = $latestID >= key($powers) ? $latestID:key($powers); //check powers.php
    $latestID = count(array_keys($pow2[4][1], $latestID + 1)) > 0 ? $latestID + 1:$latestID; //check topsh

    $latestName = array_search($latestID, $pow2[6][1]);
    if(!$latestName) {
        $latestName = "Unknown";
    }
    $status = "UNRELEASED";

    if ($pow2[0][1]['id'] == $latestID) {
        if (!empty($pow2[0][1]['text'])) {
            $status = str_replace(['[', ']'], '', $pow2[0][1]['text']);
        } else {
            $status = "UNLIMITED";
        }
    }

    $pawns = $smilies = [];
    foreach ($pow2[7][1] as $hatCode => $pawnInfo) {
        if ($hatCode !== 'time' && $pawnInfo[0] == $latestID) {
            $pawns[] = 'h' . $hatCode;
        }
    }

    if (isset($powers[$latestID])) {
        $latestName =  $powers[$latestID]['s'];// fail safe

        if (isset($powers[$latestID]['r'])) {
            $status = $powers[$latestID]['r'] > 0 ? "LIMITED" : $status;
        }

        if (isset($powers[$latestID]['f'])) {
            $status = $powers[$latestID]['f'] & 0x2000 ? "LIMITED" : $status;
        }

        $storePrice = isset($powers[ $latestID]['x']) ? $powers[$latestID]['x'].  ' xats' : $powers[$latestID]['d'] . ' days';
    }
    $smilies = array_merge(array($latestName), array_keys($pow2[4][1], $latestID));
    $implode = [
        ucfirst($latestName) . ' (ID: '. $latestID . ')',
        'Pawns: ' . implode(', ', $pawns),
        'Smilies: ' . implode(', ', $smilies),
        'Store price: ' . (isset($storePrice) ? $storePrice : "Unknown"),
        'Status: ' . $status
    ];
    
    $bot->network->sendMessageAutoDetection($who, implode(' | ', $implode), $type);
};
