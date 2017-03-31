<?php

$wallet = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'wallet')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    $user = $bot->users[$who]; 

    if (isset($message[1]) || !empty($message[1])) {
        if (is_numeric($message[1]) && isset($bot->users[$message[1]])) {
            $user = $bot->users[$message[1]];
        } else {
            foreach ($bot->users as $id => $object) {
                if (is_object($object) && strtolower($object->getRegname()) == strtolower($message[1])) {
                    $user = $object;
                    break;
                }
            }
        }
    } else {
        $user = $bot->users[$who];
    }
    
    if (isset($user)) {
        $display = ($user->isRegistered() ? $user->getRegname() : $user->getID());
        if ($user->hasPower(27) && !($user->getXats() + $user->getDays() == 0)) {
            return $bot->network->sendMessageAutoDetection($who, $display . " has " . number_format($user->getXats()) . " xats and " . number_format($user->getDays()) . " days.", $type);
        }
        return $bot->network->sendMessageAutoDetection($who, $display . " dosent have the power show or its disabled.", $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type, true);
    }
};