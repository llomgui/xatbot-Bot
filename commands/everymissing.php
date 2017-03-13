<?php

$everymissing = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

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
        $base64 = base64_encode(implode('.', array_values($user->getPowers())));
        $link = 'https://oceanproject.fr/pages/powersmissing/everyp/' . $base64 . '/';
        $bot->network->sendMessageAutoDetection($who, $link, $type);
    } else {
        $bot->network->sendMessageAutoDetection($who, 'That user is not here', $type, true);
    }
};
