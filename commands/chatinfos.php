<?php

$chatinfos = function (int $who, array $message, int $type) {

    $bot = actionAPI::getBot();

    if (!$bot->minrank($who, 'chatinfos')) {
        return $bot->network->sendMessageAutoDetection($who, 'Sorry you do not have enough rank to use this command!', $type);
    }

    if (empty($message[1]) || empty($message[2])) {
        return $bot->network->sendMessage('Usage: !chatinfos [background/language/radio/button/description] [group name]');
    }

    $ctx      = stream_context_create(array('http' => array('timeout' => 1)));
    $json     = file_get_contents('http://xat.com/web_gear/chat/roomid.php?v2&d='.$message[2], false, $ctx);
    $elements = json_decode($json);

    if (!is_numeric($elements->id)) {
        return $bot->network->sendMessageAutoDetection($who, 'Chat not found', $type);
    }

    $infos = explode(';=', $elements->a);

    switch ($message[1]) {
        case 'background':
            if (empty($infos[0])) {
                $bot->network->sendMessageAutoDetection($who, 'No background for this chat.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'The background for the chat '.$elements->g.': _'.$infos[0], $type);
            }
            break;

        case 'radio':
            if (empty($infos[4])) {
                $bot->network->sendMessageAutoDetection($who, 'No radio for this chat.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'The radio for the chat '.$elements->g.': _'.$infos[4], $type);
            }
            break;

        case 'button':
            if ($infos[5] == '- Cant') {
                $bot->network->sendMessageAutoDetection($who, 'No button color for this chat.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'The button color for the chat '.$elements->g.': _'.$infos[5], $type);
            }
            break;

        case 'language':
            if (empty($infos[3])) {
                $bot->network->sendMessageAutoDetection($who, 'No language for this chat.', $type);
            } else {
                $bot->network->sendMessageAutoDetection($who, 'The language for the chat '.$elements->g.': _'.$infos[3], $type);
            }
            break;

        case 'description':
            if (empty($elements->d)) {
                $bot->network->sendMessageAutoDetection($who, 'No description for this chat.', $type);
            }

            $bot->network->sendMessageAutoDetection($who, 'the description for the chat '.$elements->g.': '.$elements->d, $type);
            break;
    }

};
