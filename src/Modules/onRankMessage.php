<?php

use OceanProject\Bot\XatVariables;
use OceanProject\API\DataAPI;
use OceanProject\Models\Log;

$onRankMessage = function (array $array) {
    $bot = OceanProject\API\ActionAPI::getBot();

    if (isset($array['d'])) {
        $log = new Log;
    }
    $log->chatid = $bot->data->chatid;
    $log->chatname = $bot->data->chatname;
    $log->typemessage = 4;

    if (isset($array['u'])) {
        if (isset($bot->users[$array['u']])) {
            $regname = $bot->users[$array['u']]->getRegname();
            $user1 = (!is_null($regname) ? $regname . ' (' . $array['u'] . ')' : $array['u']);
        } else {
            $user1 = $array['u'];
        }
    }

    if (isset($array['d'])) {
        if (isset($bot->users[$array['d']])) {
            $regname2 = $bot->users[$array['d']]->getRegname();
            $user2 = (!is_null($regname2) ? $regname2 . ' (' . $array['d'] . ')' : $array['d']);
        } else {
            $user2 = $array['d'];
        }
    }
    
    if ($array['t'][0] == '/') {
        if (substr($array['t'], 0, 3) == '/ka') {
            $log->message = '[Rank] ' . $user1 . ' used kickall. (' . $array['t'] . ')';
            $log->save();
            return;
        }

        if (substr($array['t'], 0, 3) == '/gd') {
            $log->message = '[Rank] ' . $user1 . ' badged ' . $user2 . '!';
            $log->save();
            return;
        }

        if (substr($array['t'], 0, 3) == '/gn') {
            $log->message = '[Rank] ' . $user1 . ' banned ' . $user2 . ' for ' .
                    round(substr($array['t'], 3) / 3600, 2) . ' hours reason: "' . (!empty($array['p']) ?? '') . '"';
            $log->save();
            return;
        }

        switch (substr($array['t'], 0, 2)) {
            case '/u':
                // <m  t="/u" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' unbanned ' . $user2 . '!';
                break;

            case '/g':
                //<m  p="" t="/g3600" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' banned ' . $user2 . ' for ' .
                    round(substr($array['t'], 2) / 3600, 2) . ' hours reason: "' . (!empty($array['p']) ?? '') . '"';
                break;

            case '/m':
                $type = substr($array['p'], 0, 1);
                $hours = '';
                if (strlen($array['p']) > 1) {
                    $hours = round(substr($array['p'], 1) / 3600, 2);
                }

                switch ($type) {
                    case 'M':
                    case 'o':
                        // <m u="412345607" d="586552" t="/m" p="M"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' an owner' .
                            (!empty($hours) ? ' for ' . $hours . ' hour(s)!' : '!');
                        break;

                    case 'm':
                        // <m u="412345607" d="586552" t="/m" p="m"  />
                        // <m u="10101" d="1497708246" t="/m" p="m3600" />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a moderator' .
                            (!empty($hours) ? ' for ' . $hours . ' hour(s)!' : '!');
                        break;

                    case 'e':
                        // <m u="412345607" d="586552" t="/m" p="e"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a member' .
                            (!empty($hours) ? ' for ' . $hours . ' hour(s)!' : '!');
                        break;

                    case 'r':
                        // <m u="412345607" d="586552" t="/m" p="r"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a guest' .
                            (!empty($hours) ? ' for ' . $hours . ' hour(s)!' : '!');
                        break;
                }
                break;

            case '/k':
                // <m  p="test" t="/k" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' kicked ' . $user2 . ' reason: "' . $array['p'] . '"';
                if ($array['u'] != XatVariables::getXatid()) {
                    if (!DataAPI::isSetVariable('kicks_' . $who)) {
                        DataAPI::set('kicks_' . $who, 1);
                    } else {
                        DataAPI::set('kicks_' . $who, DataAPI::get('kicks_' . $who) + 1);
                    }
                }
                break;

            default:
                return;
                break;
        }

        $log->save();
    }
};
