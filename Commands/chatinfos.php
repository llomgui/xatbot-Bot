<?php

$chatinfos = function (int $who, array $message, int $type) {

    $bot = OceanProject\Bot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'chatinfos')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1]) || empty($message[2])) {
        return $bot->network->sendMessage(
            'Usage: !chatinfos [background/language/radio/button/description] [group name]'
        );
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
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.notfound', ['background']), $type
                );
            } else {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.found', ['background', $elements->g, $infos[0]]), $type
                );
            }
            break;

        case 'radio':
            if (empty($infos[4])) {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.notfound', ['radio']), $type
                );
            } else {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.found', ['radio', $elements->g, $infos[4]]), $type
                );
            }
            break;

        case 'button':
            if ($infos[5] == '- Cant') {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.notfound', ['button color']), $type
                );
            } else {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.found', ['button color', $elements->g, $infos[5]]), $type
                );
            }
            break;

        case 'language':
            if (empty($infos[3])) {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.notfound', ['language']), $type
                );
            } else {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.found', ['language', $elements->g, $infos[3]]), $type
                );
            }
            break;

        case 'description':
            if (empty($elements->d)) {
                $bot->network->sendMessageAutoDetection(
                    $who, $bot->botlang('cmd.chatinfos.notfound', ['description']), $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who, $bot->botlang('cmd.chatinfos.found', ['description', $elements->g, $elements->d]), $type);
            break;
    }
};
