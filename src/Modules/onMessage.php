<?php

use OceanProject\Models\Log;
use OceanProject\API\DataAPI;
use OceanProject\Bot\XatVariables;

$onMessage = function (int $who, string $message) {

    if (in_array(substr($message, 0, 2), ['/d', '/m'])) {
        return;
    }

    $bot = OceanProject\API\ActionAPI::getBot();

    $regname = $bot->users[$who]->getRegname();

    $log = new Log;
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 1;
    $log->message = '[Main] ' . (!is_null($regname) ? $regname . ' (' . $who . ')' : $who) . ' sent: "' .
        $message . '"';
    $log->save();

    if ($bot->isPremium && $bot->data->premium < time()) {
        $bot->network->sendMessage('Ah! My premium time is over (cry2)');
        return $bot->refresh();
    }

    $message = strtolower($message);
    $message2 = explode(' ', $message);
    
    if (!DataAPI::isSetVariable('lastMessage')) {
        DataAPI::set('lastMessage', $who);
    } else {
        if (DataAPI::get('lastMessage') != $who) {
            DataAPI::set('lastMessage', $who);
            DataAPI::set('countMessage', 1);
        }
    }
    
    if ($bot->data->togglemoderation) {
        if ($bot->flagToRank($who) < $bot->stringToRank($bot->chatInfo['rank'])) {
            if (isset($bot->data->maxflood) && $bot->data->maxflood > 1) {
                if (!DataAPI::isSetVariable('countMessage')) {
                    DataAPI::set('countMessage', 1);
                } else {
                    $value = DataAPI::get('countMessage');
                    DataAPI::set('countMessage', ++$value);
                    if ($value >= $bot->data->maxflood) {
                        DataAPI::set('countMessage', 0);
                        return $bot->network->kick($who, 'You are not allowed to flood!');
                    }
                }
            }
  
            foreach ($message2 as $value) {
                if (isset($bot->data->maxchar) && $bot->data->maxchar > 0) {
                    if (strpos($value, 'ffffff') || strpos($value, '------') || strpos($value, '000000')) {
                    } else {
                        if (preg_match_all('/(.)\1{' . $bot->data->maxchar . ',}/iu', $value)) {
                            return $bot->network->kick(
                                $who,
                                'You are not allowed to spam! (maxChar: ' . $bot->data->maxchar . ')'
                            );
                        }
                    }
                }
            }
  
            if ($bot->data->togglelinkfilter === true) {
                $bool = false;
                $allowedWebsites = (sizeof($bot->linksfilter) > 0) ? $bot->linksfilter :  ['oceanproject.fr'];
                $pattern = "/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,}(\/\S*)?/";
                foreach ($message2 as $value) {
                    if (preg_match($pattern, $value)) {
                        foreach ($allowedWebsites as $website) {
                            if (strpos($value, $website) !== false) {
                                $bool = true;
                                break;
                            }
                        }
                        if (!$bool) {
                            return $bot->network->kick(
                                $who,
                                'You are not allowed to send links!'
                            );
                        }
                    }
                }
            }
  
            if (isset($bot->data->maxsmilies) && $bot->data->maxsmilies > 1) {
                $count = 0;
                $count += preg_match_all('/\([^ ]+\)/', $message, $matches);
                $count += preg_match_all('/:-?(P|p|S|s|O|o|D|d|@|\[|\$|\)|\(|\'\(|\||\*)/', $message, $matches);
                if ($count > $bot->data->maxsmilies) {
                    return $bot->network->kick(
                        $who,
                        'You are not allowed to send more than ' . $bot->data->maxsmilies . ' smilies per message.'
                    );
                }
            }
  
            if (sizeof($bot->badwords) > 0) {
                for ($i = 0; $i < sizeof($bot->badwords); $i++) {
                    if (strpos($message, strtolower($bot->badwords[$i]['badword'])) !== false) {
                        DataAPI::set(
                            'modproof',
                            'User: ' . ((!is_null($regname)) ?
                              $regname . ' (' . $who . ')' : $who) . ' Message: ' . $message
                        );
  
                        switch ($bot->badwords[$i]['method']) {
                            case 'ban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !'
                                );
                                break;
  
                            case 'kick':
                                return $bot->network->kick(
                                    $who,
                                    'Do not say inapp words :o !
                                  '
                                );
                                break;
  
                            case 'dunce':
                                return $bot->network->ban(
                                    $who,
                                    0,
                                    'Do not say inapp words :o !',
                                    'gd'
                                );
                                break;
  
                            case 'zap':
                                return $bot->network->kick(
                                    $who,
                                    'Do not say inapp words :o !',
                                    '#rasberry#bump'
                                );
                                break;
  
                            case 'reverse':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    176
                                );
                                break;
  
                            case 'yellowcard':
                                return $bot->network->ban(
                                    $who,
                                    0,
                                    'Do not say inapp words :o !',
                                    'gy'
                                );
                                break;
  
                            case 'badge':
                                return $bot->network->sendPrivateConversation(
                                    $who,
                                    '/nb' . 'Do not say inapp words :o !'
                                );
                                break;
  
                            case 'naughtystep':
                                return $bot->network->ban(
                                    $who,
                                    0,
                                    'Do not say inapp words :o !',
                                    'gn'
                                );
                                break;
  
                            case 'snakeban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    134
                                );
                                break;
  
                            case 'spaceban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    136
                                );
                                break;
  
                            case 'matchban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    140
                                );
                                break;
  
                            case 'codeban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    162
                                );
                                break;
  
                            case 'mazeban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    152
                                );
                                break;
  
                            case 'slotban':
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !',
                                    'g',
                                    236
                                );
                                break;
                          
                            default:
                                return $bot->network->ban(
                                    $who,
                                    $bot->badwords[$i]['hours'],
                                    'Do not say inapp words :o !'
                                );
                                break;
                        }
                    }
                }
            }
        }
    }

    if (!empty($bot->responses)) {
        $replace = [
            '{name}'    => $bot->users[$who]->getNick(),
            '{status}'  => $bot->users[$who]->getStatus(),
            '{regname}' => $bot->users[$who]->getRegname() ?? $bot->users[$who]->getID(),
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
                            foreach ($replace as $k => $v) {
                                $responses = str_replace(strtolower($k), $v, $responses);
                            }

                            return $bot->network->sendMessage($responses[$key]);
                        }
                    }
                }
            }
        }
    }

    if (in_array($bot->data->toggleradio, ['scroll', 'main'])) {
        $bool = false;
        if (!DataAPI::isSetVariable('radio')) {
            DataAPI::set('radio', ['lastCheck' => 0]);
        } else {
            $infos = DataAPI::get('radio');
            if ($infos['lastCheck'] <= time()) {
                $song = $bot->getCurrentSong();

                if ($song == false) {
                    return $bot->network->sendMessage(
                        'You have an error with the radio!
                        If you don\'t want to use this feature, set it to OFF in panel.'
                    );
                }

                DataAPI::set('radio', $song);

                $before = ($bot->data->toggleradio == 'scroll' ? '/s' : '');
                $bot->network->sendMessage(
                    $before . 'Listening to: ' . $song['song'] . ' ' . $song['listeners'] . '/' . $song['max'] . '.'
                );
                $bool = true;
            }
        }
    }

    return;
};
