<?php
$misc = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'misc')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !misc [reserve/chatid/chatname/xatid/regname/promo] [info]',
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

                $promoMessage .= ' ' . $group->n . ' [' . (isset($group->t) ?
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
    }
};
