<?php

use xatbot\Bot\XatVariables;

$misc = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'misc')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !misc [reserve/chatid/chatname/xatid/regname/promo/delistcheck] [info]',
            $type,
            true
        );
    }

    switch (strtolower($message[1])) {
        case 'reserve':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2]) || $message[2] < 1) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !misc reserve [xats]', $type, true);
            }

            $xats = $message[2];
            $days = (ceil($xats / 50));

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.reserve', [
                    $message[2],
                    ($xats > 1 ? 'xats' : 'xat'),
                    (ceil($message[2] / 50)),
                    ($days > 1 ? 'days' : 'day')
                ]),
                $type
            );
            break;

        case 'chatid':
            if (!isset($message[2]) || empty($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !misc chatid [chat name]', $type, true);
            }

            $json = json_decode(file_get_contents('https://xat.com/web_gear/chat/roomid.php?d=' . $message[2].'&v2'));

            if (!$json) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('chat.notfound'), $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.chatid.result', [
                    $json->g,
                    $json->id
                ]),
                $type
            );
            break;

        case 'chatname':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !misc chatname [chat id]', $type, true);
            }

            $json = json_decode(file_get_contents('https://xat.com/web_gear/chat/roomid.php?i=' . $message[2].'&v2'));

            if (!$json) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('chat.notfound'), $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.chatname.result', [
                    $json->id,
                    $json->g
                ]),
                $type
            );
            break;

        case 'xatid':
            if (!isset($message[2]) || empty($message[2]) || is_numeric($message[2]) || is_numeric($message[2][0])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !misc xatid [regname]', $type, true);
            }

            $id = file_get_contents('https://xat.me/x?name=' . $message[2]);

            if (!$id) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.notfound'), $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.xatid', [
                    ucfirst($message[2]),
                    $id
                ]),
                $type
            );
            break;

        case 'regname':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !misc regname [id]', $type, true);
            }

            $reg = file_get_contents('https://xat.me/x?id=' . $message[2]);

            if (!$reg) {
                return $bot->network->sendMessageAutoDetection($who, $bot->botlang('user.notfound'), $type);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.regname', [
                    $message[2],
                    $reg
                ]),
                $type
            );
            break;

        case 'promo':
            $promo = json_decode(file_get_contents("https://xat.com/json/promo.php"));
            $message[2] = isset($message[2]) ? $message[2] : 'en';
            switch (strtolower(trim($message[2]))) {
                case "english":
                case "en":
                    $lang = $promo->en;
                    $language = "English";
                    break;
                case "spanish":
                case "es":
                    $lang = $promo->es;
                    $language = "Spanish";
                    break;
                case "italian":
                case "it":
                    $lang = $promo->it;
                    $language = "Italian";
                    break;
                case "arabic":
                case "ar":
                    $lang = $promo->ar;
                    $language = "Arabic";
                    break;
                case "french":
                case "fr":
                    $lang = $promo->fr;
                    $language = "French";
                    break;
                case "portuguese":
                case "pt":
                    $lang = $promo->pt;
                    $language = "Portuguese";
                    break;
                case "romanian":
                case "ro":
                    $lang = $promo->ro;
                    $language = "Romanian";
                    break;
                case "thai":
                case "th":
                    $lang = $promo->th;
                    $language = "Thai";
                    break;
                default:
                    $lang = $promo->en;
                    $language = "English";
                    break;
            }

            $promoMessage = "";
            $count = 0;

            foreach ($lang as $group) {
                if (isset($group->t)) {
                    $timeLeft = $group->t - time();
                    $hours = floor($timeLeft / 3600);
                    $minutes = floor(($timeLeft / 60) % 60);
                    $seconds = $timeLeft % 60;
                }

                $promoMessage .= $group->n . ' [' . (isset($group->t) ?
                    sprintf("%02d hours, %02d minutes and %02d seconds", $hours, $minutes, $seconds) . ' left' :
                    "Auto promoted") . '], ';
                $count++;
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.promoted', [
                    $count,
                    $language,
                    ($count > 1 ? 'chats' : 'chat'),
                    rtrim($promoMessage, ', ')
                ]),
                $type
            );
            break;
        case 'delistcheck':
            if (!isset($message[2]) || empty($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !misc delistcheck [chat name]',
                    $type
                );
            }
            $stream = [];
            $blank = '';
            $POST['l_k2'] = $blank;
            $POST['l_dt'] = $blank;
            $POST['Xats'] = '100';
            $POST['YourEmail'] = XatVariables::getRegname();
            $POST['password'] = XatVariables::getPassword();
            $POST['GroupName'] = $message[2];
            $POST['Hours'] = '1';
            $POST['XatsDays'] = $blank;
            $POST['Promote'] = $blank;
            $stream['http']['method'] = 'POST';
            $stream['http']['header'] = 'Content-Type: application/x-www-form-urlencoded';
            $stream['http']['content'] = http_build_query($POST);
            $stream['http']['timeout'] = 1;
            
            $res = file_get_contents(
                'https://xat.com/web_gear/chat/promotion.php',
                false,
                stream_context_create($stream)
            );

            if (!$res) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.failurl'),
                    $type
                );
            }
            $message[2] = ucfirst($message[2]);
            if (strpos($res, '**<span data-localize=buy.membersonly>Chat is members only')) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.membersonly', [$message[2]]),
                    $type
                );
            }
            if (strpos($res, '**<span data-localize=buy.promona>Sorry, promotion not available')) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.notavailable', [$message[2]]),
                    $type
                );
            }
            if (strpos($res, '**<span data-localize=buy.langnotset>Group language is not set.')) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.langnotset', [$message[2]]),
                    'The chat "' . $message[2] . '" has no lang set. Please edit group to set language.',
                    $type
                );
            }
            if (strpos($res, '**<span data-localize=buy.groupnoexist>That group doesn\'t exist')) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.notfound', [$message[2]]),
                    $type
                );
            }
            if (strpos($res, '**<span data-localize=buy.delisted>Chat is delisted. Please re-list.')) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.misc.delistcheck.delisted', [$message[2]]),
                    $type
                );
            }
            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.misc.delistcheck.canpromote', [$message[2]]),
                $type
            );
            break;
    }
};
