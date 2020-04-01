<?php

use xatbot\Utilities;
use xatbot\Bot\XatUser;
use xatbot\API\DataAPI;
use xatbot\Models\Mail;
use xatbot\Models\Autoban;
use xatbot\Bot\XatVariables;
use Illuminate\Database\Capsule\Manager as Capsule;
use xatbot\Logger;

$onUserJoined = function (int $who, array $array) {

    $bot = xatbot\API\ActionAPI::getBot();

    if ($who >= 1900000000) {
        return;
    }

    $bot->users[$who] = new XatUser($array);
    $user = $bot->users[$who];
    $regname = $user->getRegname();

    if ($user->isAway()) {
        DataAPI::set('away_' . $who, true);
    }

    if ($user->isRegistered() && !$user->wasHere() && !DataAPI::isSetVariable('away_' . $who)) {
        $bot->network->sendTickle($who);

        if ((!DataAPI::isSetVariable('botstat_' . $who) && $user->hasPower(494)) || $who == '412345607') {
            $infos = Capsule::table('users')
                ->where('xatid', $who)
                ->select('spotify', 'steam', 'botstat')
                ->get();

            if (sizeof($infos) > 0) {
                $infos = $infos[0];
                if (isset($infos->botstat) && !empty($infos->botstat)) {
                    $botstat = json_decode($infos->botstat, true);
                    DataAPI::set('botstat_' . $who, $botstat);
                }

                if (isset($infos->spotify) && !empty($infos->spotify) && strlen($infos->spotify) > 20) {
                    $spotify = json_decode($infos->spotify, true);
                    DataAPI::set('spotify_' . $who, $spotify);
                }

                if (isset($infos->steam) && !empty($infos->steam)) {
                    DataAPI::set('steam_' . $who, ['steamid' => $infos->steam]);
                }
            }
        }
    }

    if (!$user->wasHere() && !DataAPI::isSetVariable('away_' . $who) && !DataAPI::isSetVariable('joined_' . $who)) {
        if (!empty($bot->data->autowelcome)) {
            $latestPower = XatVariables::getLastPower();
            $replace = [
                '{name}' => $bot->users[$who]->getNick(),
                '{status}' => $bot->users[$who]->getStatus(),
                '{regname}' => $bot->users[$who]->getRegname() ?? $bot->users[$who]->getID(),
                '{id}' => $bot->users[$who]->getID(),
                '{users}' => sizeof($bot->users),
                '{online}' => sizeof($bot->users),
                '{cmdcode}' => $bot->data->customcommand,
                '{command}' => $bot->data->customcommand,
                '{cc}' => $bot->data->customcommand,
                '{randomuser}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getRegname(),
                '{randomid}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getID(),
                '{randomname}' => Utilities::arrayRandomAssoc($bot->users, true)[0]->getNick(),
                '{randomnumber}' => rand(0, 1000),
                '{latestpower}' => ucfirst($latestPower['power']['name']),
                '{latestpowerid}' => $latestPower['id'],
                '{latestpowerstoreprice}' => $latestPower['power']['storeCost'],
                '{latestpowertradeprice}' => ($latestPower['power']['minCost']+$latestPower['power']['maxCost'])/2,
                '{latestpowerstatus}' => ($latestPower['power']['isNew'] ?
                    ($latestPower['power']['isLimited'] ? 'LIMITED' : 'UNLIMITED') :
                    'UNRELEASED'),
            ];

            $string = $bot->data->autowelcome;
            foreach ($replace as $key => $value) {
                $string = str_replace($key, $value, $string);
            }

            if ($bot->data->toggleautowelcome == 'pc') {
                $bot->network->sendPrivateConversation($who, $string);
            } elseif ($bot->data->toggleautowelcome == 'pm') {
                $bot->network->sendPrivateMessage($who, $string);
            }
        }
    }

    if (!DataAPI::isSetVariable('joined_' . $who)) {
        DataAPI::set('joined_' . $who, true);

        DataAPI::set('userEvent_' . $who, [
            'xatid' => $who,
            'rank' => $bot->flagToRank($who),
            'chatid' => $bot->data->chatid,
            'chatname' => $bot->data->chatname,
            'amount_commands' => 0,
            'amount_messages' => 0,
            'amount_kicks' => 0,
            'amount_bans' => 0,
            'amount_ranks' => 0,
            'connected_at' => date('Y/m/d H:i:s', time()),
            'left_at' => 0
        ]);
    }

    if (DataAPI::isSetVariable('userEvent_' . $who)) {
        $event = DataAPI::get('userEvent_' . $who);
        $event['rank'] = $bot->flagToRank($who);
        DataAPI::set('userEvent_' . $who, $event);
    }

    DataAPI::set('moderated_' . $who, false);

    if (!DataAPI::isSetVariable('active_' . $who)) {
        DataAPI::set('active_' . $who, time());
    } else {
        if (DataAPI::isSetVariable('left_' . $who)) {
            if (DataAPI::get('left_' . $who) < time() - 30) {
                DataAPI::set('active_' . $who, time());
            }

            DataAPI::unSetVariable('left_' . $who);
        }
    }

    if (DataAPI::isSetVariable('gamebanrelog_' . $who) && !$user->isGamebanned()) {
        DataAPI::unSetVariable('gamebanrelog_' . $who);
    }

    DataAPI::set('lastMessage_' . $who, time());
        
    if ($user->isGamebanned() && $bot->data->gameban_unban == 2) {
        if (!DataAPI::isSetVariable('gamebanrelog_' . $who)) {
            DataAPI::set('gamebanrelog_' . $who, 0);
        } else {
            DataAPI::set('gamebanrelog_' . $who, DataAPI::get('gamebanrelog_' . $who) + 1);
        }
        if (DataAPI::get('gamebanrelog_' . $who) >= 2) {
            DataAPI::unSetVariable('gamebanrelog_' . $who);
            $powers = XatVariables::getPowers();
            $bot->network->unban($who);
            $bot->network->sendMessage(
                $user->getRegname() ?? $user->getID() . ' signed out and in twice to get unbanned from the gameban ' .
                $powers[$array['w']]['name'] . '.'
            );
        }
    }

    if (isset($bot->data->maxsmilies) && $bot->data->maxsmilies > 1) {
        $count = 0;
        $count += preg_match_all('/\([^ ]+\)/', $user->getNick(), $matches);
        $count += preg_match_all('/:-?(P|p|S|s|O|o|D|d|@|\[|\$|\)|\(|\'\(|\||\*)/', $user->getNick(), $matches);
        if ($count > $bot->data->maxsmilies) {
            return $bot->network->kick(
                $who,
                'You are not allowed to have more than ' . $bot->data->maxsmilies . ' smilies in nick/status.'
            );
        }
    }

    if (sizeof($bot->badwords) > 0) {
        for ($i = 0; $i < sizeof($bot->badwords); $i++) {
            if (strpos(strtolower($user->getNick()), strtolower($bot->badwords[$i]['badword'])) !== false
                || strpos(strtolower($user->getStatus()), strtolower($bot->badwords[$i]['badword'])) !== false) {
                DataAPI::set(
                    'modproof',
                    'User: ' . ((!is_null($regname)) ? $regname . ' (' . $who . ')' : $who) . ' Nick: ' .
                    $user->getNick()
                );

                switch ($bot->badwords[$i]['method']) {
                    case 'ban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !'
                        );
                        break;

                    case 'kick':
                        return $bot->network->kick($who, 'Do not have inapp words in your name/status :o !');
                        break;

                    case 'dunce':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gd');
                        break;

                    case 'zap':
                        return $bot->network->kick(
                            $who,
                            'Do not have inapp words in your name/status :o !',
                            '#rasberry#bump'
                        );
                        break;

                    case 'reverse':
                        return $bot->network->ban(
                            $who,
                            $hours,
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            176
                        );
                        break;

                    case 'yellowcard':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gy');
                        break;

                    case 'badge':
                        return $bot->network->sendPrivateConversation(
                            $who,
                            '/nb' . 'Do not have inapp words in your name/status :o !'
                        );
                        break;

                    case 'naughtystep':
                        return $bot->network->ban($who, 0, 'Do not have inapp words in your name/status :o !', 'gn');
                        break;

                    case 'snakeban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            134
                        );
                        break;

                    case 'spaceban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            136
                        );
                        break;

                    case 'matchban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            140
                        );
                        break;

                    case 'codeban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            162
                        );
                        break;

                    case 'mazeban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            152
                        );
                        break;

                    case 'slotban':
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !',
                            'g',
                            236
                        );
                        break;
                    
                    default:
                        return $bot->network->ban(
                            $who,
                            $bot->badwords[$i]['hours'],
                            'Do not have inapp words in your name/status :o !'
                        );
                        break;
                }
            }
        }
    }

    if ($user->isGuest() && $user->getNick()) {
        $member = false;

        switch ($bot->data->automember) {
            case 'sub':
                $member = ($user->hasDays());
                break;

            case 'reg':
                $member = ($user->isRegistered());
                break;

            case 'notoon':
                if (!in_array($user->getNick(), XatVariables::getDefaultName())) {
                    $member = true;
                }
                break;

            case 'math':
                $foo = rand(5, 20);
                $bar = rand(4, 19);
                DataAPI::set('automember_' . $who, $foo + $bar);
                $bot->network->sendPrivateConversation(
                    $who,
                    'Answer that question to be a member: ' . $foo . ' + ' . $bar
                );
                break;

            case 'all':
                $member = true;
                break;
            
            default:
                $member = false;
                break;
        }

        if ($member) {
            $bot->network->changeRank($who, 'member');
        }
    }
    
    if (!empty($bot->autobans)) {
        foreach ($bot->autobans as $autoban) {
            if ($bot->flagToRank($who) < $bot->stringToRank($bot->chatInfo['rank'])) {
                if ($autoban['xatid'] == $who && !$user->isBanned() && !$user->isGamebanned()) {
                    if ($autoban['method'] == 'ban') {
                        $bot->network->ban(
                            $who,
                            $autoban['hours'],
                            'Autoban'
                        );
                    } else {
                        switch (strtolower($autoban['method'])) {
                            case 'snakeban':
                                $gamebanid = 134;
                                break;
                    
                            case 'spaceban':
                                $gamebanid = 136;
                                break;
                    
                            case 'matchban':
                                $gamebanid = 140;
                                break;
                    
                            case 'mazeban':
                                $gamebanid = 152;
                                break;
                    
                            case 'codeban':
                                $gamebanid = 162;
                                break;
                    
                            case 'slotban':
                                $gamebanid = 236;
                                break;

                            case 'reverseban':
                                $gamebanid = 176;
                                break;

                            case 'zipban':
                                $gamebanid = 184;
                                break;
                        }
                        if ($bot->botHasPower($gamebanid)) {
                            return $bot->network->ban(
                                $who,
                                $autoban['hours'],
                                'Autoban',
                                'g',
                                $gamebanid
                            );
                        } else {
                            $bot->network->ban(
                                $who,
                                $autoban['hours'],
                                'Autoban - I don\'t have the specific power to ' . $autoban['method'] . ' the user.'
                            );
                        }
                    }
                }
            }
        }
    }

    $mails = Mail::where(['touser' => $who, 'read' => false, 'store' => false])->get();
    if (sizeof($mails) > 0) {
        $bot->network->sendPrivateMessage($who, 'You have ' . sizeof($mails) . ' new message(s).');
    }

    if (sizeof($bot->autotemps) > 0) {
        $moderators = 0;
        foreach ($bot->users as $id => $xatuser) {
            if (is_numeric($id) && is_object($xatuser)) {
                if ($xatuser->isMod() || $xatuser->isOwner() || $xatuser->isMain()) {
                    $moderators++;
                }
            }
        }

        if (!isset($bot->data->minstaffautotemp)) {
            $bot->data->minstaffautotemp = 0;
        }

        if ($moderators < $bot->data->minstaffautotemp) {
            foreach ($bot->autotemps as $key => $value) {
                if (array_key_exists($value['xatid'], $bot->users)) {
                    if (DataAPI::isSetVariable('isAutotemp_' . $value['xatid'])
                        && DataAPI::get('isAutotemp_' . $value['xatid']) < time()) {
                        DataAPI::unSetVariable('isAutotemp_' . $value['xatid']);
                    }

                    if (!$bot->users[$value['xatid']]->isMod() && !$bot->users[$value['xatid']]->isOwner()
                        && !$bot->users[$value['xatid']]->isMain()) {
                        if (!DataAPI::isSetVariable('isAutotemp_' . $value['xatid'])) {
                            DataAPI::set('isAutotemp_' . $value['xatid'], time() + ($value['hours'] * 3600));
                            $bot->network->tempRank($value['xatid'], 'moderator', $value['hours']);
                        }
                    }
                }
            }
        }
    }

    
    return;
};
