<?php

use xatbot\API\DataAPI;

$onModeration = function (int $who, string $message) {

    $bot = xatbot\API\ActionAPI::getBot();

    if ($bot->isPremium && $bot->data->premium < time()) {
        $bot->network->sendMessage('Ah! My premium time is over (cry2)');
        return $bot->refresh();
    }

    $message = strtolower($message);
    $message2 = explode(' ', $message);

    $regname = $bot->users[$who]->getRegname();

    if (!DataAPI::isSetVariable('moderated_' . $who)) {
        DataAPI::set('moderated_' . $who, false);
    }
    
    if (!DataAPI::isSetVariable('lastMessage')) {
        DataAPI::set('lastMessage', $who);
    } else {
        if (DataAPI::get('lastMessage') != $who) {
            DataAPI::set('lastMessage', $who);
            DataAPI::set('countMessage', 1);
        }
    }

    if (!DataAPI::isSetVariable('lastAutoMessage') && !is_null($bot->data->automessagetime)
        && $bot->data->automessagetime > 0 && !empty($bot->data->automessage)) {
        DataAPI::set('lastAutoMessage', time() + $bot->data->automessagetime * 60);
    }

    if (DataAPI::isSetVariable('lastAutoMessage') && DataAPI::get('lastAutoMessage') < time()
        && !empty($bot->data->automessage)) {
        $bot->network->sendMessage($bot->data->automessage);
        DataAPI::set('lastAutoMessage', time() + $bot->data->automessagetime * 60);
    }

    DataAPI::set('lastMessage_' . $who, time());

    // Check if user (moderator) sent a message in the last x minutes
    if (is_int($bot->data->kickafk_minutes) && $bot->data->kickafk_minutes >= 5) {
        foreach ($bot->users as $id => $user) {
            if ($user->isMod() && DataAPI::isSetVariable('lastMessage_' . $id)) {
                $time = DataAPI::get('lastMessage_' . $id);
                if ($time + ($bot->data->kickafk_minutes * 60) < time()) {
                    if (!DataAPI::isSetVariable('kickAFK_' . $id)) {
                        $bot->network->sendPrivateConversation(
                            $id,
                            'You need to answer this message or you will be kicked in the next 30 seconds. (bump)'
                        );
                        DataAPI::set('kickAFK_' . $id, time() + 30);
                    }
                }
            }

            if (DataAPI::isSetVariable('kickAFK_' . $id)) {
                if (DataAPI::get('kickAFK_' . $id) < time()) {
                    DataAPI::unSetVariable('kickAFK_' . $id);
                    $bot->network->kick(
                        $id,
                        'You did not send any message in the last ' . $bot->data->kickafk_minutes . ' minutes.'
                    );
                }
            }
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
                        DataAPI::set('moderated_' . $who, true);
                        return $bot->network->kick($who, 'You are not allowed to flood!');
                    }
                }
            }
  
            foreach ($message2 as $value) {
                if (isset($bot->data->maxchar) && $bot->data->maxchar > 0) {
                    if (strpos($value, 'ffffff') || strpos($value, '------') || strpos($value, '000000')) {
                    } else {
                        if (preg_match_all('/(.)\1{' . $bot->data->maxchar . ',}/iu', $value)) {
                            DataAPI::set('moderated_' . $who, true);
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
                $allowedWebsites = (sizeof($bot->linksfilter) > 0) ? $bot->linksfilter :  ['xatbot.fr'];
                $pattern = "/([a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/";
                foreach ($message2 as $value) {
                    if (preg_match($pattern, $value)) {
                        foreach ($allowedWebsites as $website) {
                            if (strpos($value, $website) !== false) {
                                $bool = true;
                                break;
                            }
                        }
                        if (!$bool) {
                            DataAPI::set('moderated_' . $who, true);
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
                $count += preg_match_all('/;-?(P|p|S|s|O|o|D|d|@|\[|\$|\)|\(|\'\(|\||\*)/', $message, $matches);
                if ($count > $bot->data->maxsmilies) {
                    DataAPI::set('moderated_' . $who, true);
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
                        DataAPI::set('moderated_' . $who, true);
  
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
};
