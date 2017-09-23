<?php

use OceanProject\Bot\XatVariables;
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
        switch (substr($array['t'], 0, 2)) {
            case '/u':
                // <m  t="/u" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' unbanned ' . $user2 . '!';
                break;
                
            case '/g':
                //<m  p="" t="/g3600" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' banned ' . $user2 . ' for ' .
                    round(substr($array['t'], 2) / 3600, 2) . ' hours reason: "' . $array['p'] . '"';
                break;
                
            case '/m':
                switch ($array['p']) {
                    case 'M':
                        // <m u="412345607" d="586552" t="/m" p="M"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' an owner!';
                        break;
                        
                    case 'm':
                        // <m u="412345607" d="586552" t="/m" p="m"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a moderator!';
                        break;

                    case 'e':
                        // <m u="412345607" d="586552" t="/m" p="e"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a member!';
                        break;

                    case 'r':
                        // <m u="412345607" d="586552" t="/m" p="r"  />
                        $log->message = '[Rank] ' . $user1 . ' made ' . $user2 . ' a guest!';
                        break;
                }
                break;
                
            case '/k':
                // <m  p="test" t="/k" u="412345607" d="586552"  />
                $log->message = '[Rank] ' . $user1 . ' kicked ' . $user2 . ' reason: "' . $array['p'] . '"';
                break;
                
            default:
                return;
                break;
        }

        var_dump($log->message);
        $log->save();
    }
};
