=<?php

$onMessage = function (int $who, string $message) {

    $bot = actionAPI::getBot();

    if ($bot->isPremium && $bot->data->premium < time()) {
        $bot->network->sendMessage('Ah! My premium time is over (cry2)');
        return $bot->refresh();
    }

    $message = strtolower($message);
    $regname = $bot->users[$who]->getRegname();
    $message2 = explode(' ', $message);

    if (!dataAPI::is_set('lastMessage')) {
        dataAPI::set('lastMessage', $who);
    } else {
        if (dataAPI::get('lastMessage') != $who) {
            dataAPI::set('lastMessage', $who);
            dataAPI::set('countMessage', 1);
        }
    }

    if ($bot->flagToRank($who) < $bot->stringToRank($bot->chatInfo['rank'])) {

        if (isset($bot->data->maxflood) && $bot->data->maxflood > 1) {
            if (!dataAPI::is_set('countMessage')) {
                dataAPI::set('countMessage', 1);
            } else {
                $value = dataAPI::get('countMessage');
                dataAPI::set('countMessage', ++$value);
                if ($value >= $bot->data->maxflood) {
                    dataAPI::set('countMessage', 0);
                    return $bot->network->kick($who, 'You are not allowed to flood!');
                }
            }
        }

        foreach ($message2 as $value) {
            if (isset($bot->data->maxchar) && $bot->data->maxchar > 0) {
                if (strpos($value, 'ffffff') || strpos($value, '------') || strpos($value, '000000')) {

                } else {
                    if (preg_match_all('/(.)\1{' . $bot->data->maxchar . ',}/iu', $value)) {
                        return $bot->network->kick($who, 'You are not allowed to spam! (maxChar: ' . $bot->data->maxchar . ')');
                    }
                }
            }
        }

        if (sizeof($bot->badwords) > 0) {
            for ($i = 0; $i < sizeof($bot->badwords); $i++) { 
                if (strpos($message, strtolower($bot->badwords[$i]['badword']))) {
                    dataAPI::set('modproof', 'User: ' . ((!is_null($regname)) ? $regname . ' (' . $who . ')' : $who) . ' Message: ' . $message);

                    switch ($bot->badwords[$i]['method']) {
                        case 'ban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !');
                            break;

                        case 'kick':
                            return $bot->network->kick($who, 'Do not say inapp words :o !');
                            break;

                        case 'dunce':
                            return $bot->network->ban($who, 0, 'Do not say inapp words :o !', 'gd');
                            break;

                        case 'zap':
                            return $bot->network->kick($who, 'Do not say inapp words :o !', '#rasberry#bump');
                            break;

                        case 'reverse':
                            return $bot->network->ban($who, $hours, 'Do not say inapp words :o !', 'g', 176);
                            break;

                        case 'yellowcard':
                            return $bot->network->ban($who, 0, 'Do not say inapp words :o !', 'gy');
                            break;

                        case 'badge':
                            return $bot->network->sendPrivateConversation($who, '/nb' . 'Do not say inapp words :o !');
                            break;

                        case 'naughtystep':
                            return $bot->network->ban($who, 0, 'Do not say inapp words :o !', 'gn');
                            break;

                        case 'snakeban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;

                        case 'spaceban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;

                        case 'matchban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;

                        case 'codeban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;

                        case 'mazeban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;

                        case 'slotban':
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !', 'g', );
                            break;
                        
                        default:
                            return $bot->network->ban($who, $bot->badwords[$i]['hours'], 'Do not say inapp words :o !');
                            break;
                    }
                }
            }
        }
    }

    if (!empty($bot->responses)) {

        $replace = [
            '{name}'    => $bot->users[$who]->getNick(),
            '{status}'  => $bot->users[$who]->getStatus(), 
            '{regname}' => $bot->users[$who]->getRegname(),
            '{users}'   => sizeof($bot->users),
            '{cmdcode}' => $bot->data->customcommand,
            '{id}'      => $bot->users[$who]->getID(),
        ];

        $responses = $bot->responses;
        foreach ($responses as $key => $value) {
            if (strlen($key) == 0) {
                continue;
            }

            if ($key[0] == '*') {
                $key = substr($key, 1);

                if (strtolower($key) == $message) {
                    foreach ($replace as $k => $v) {
                        $value = str_replace(strtolower($k), $v, $value);
                    }
                    
                    return $bot->network->sendMessage($value);
                }
            } else {
                if (sizeof(explode(' ', $key)) > 1) {
                    if (stripos($message, $key) !== false) {
                        foreach ($replace as $k => $v) {
                            $responses = str_replace(strtolower($k), $v, $responses);
                        }

                        return $bot->network->sendMessage($responses[$key]);
                    }
                } else {
                    for ($i = 0; $i < sizeof($message2); $i++) {
                        if ($message2[$i] == strtolower($key)) {
                            foreach($replace as $k => $v) {
                                $responses = str_replace(strtolower($k), $v, $responses);
                            }

                            return $bot->network->sendMessage($responses[$key]);
                        }
                    }
                }
            }
        }
    }

    return;

};
