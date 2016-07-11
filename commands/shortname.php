<?php

$shortname = function ($who, $message, $type) {

    $bot = ActionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, "Usage: !shortname [name]", $type, true);
    }

    if (strlen($message[1]) < 4) {
        return $bot->network->sendMessageAutoDetection($who, "Too short for a shortname. Minimum 4 letters.", $type);
    }

    if (strlen($message[1]) > 9) {
        return $bot->network->sendMessageAutoDetection($who, "Too long for a shortname. Maximum 9 letters.", $type);
    }

    if (is_numeric($message[1][0])) {
        return $bot->network->sendMessageAutoDetection($who, "Shortnames can\'t start with a number.", $type);
    }

    $POST['GroupName'] = $message[1];
    $POST['Quote']     = 'Get+cost';
    $POST['YourEmail'] = $bot->network->logininfo['n'];
    $POST['agree']     = 'ON';
    $POST['l_dt']      = $bot->network->logininfo['dt'];
    $POST['l_k2']      = $bot->network->logininfo['k2'];
    $POST['password']  = Variables::getPassword();
    $stream = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($POST)
        ]
    ];
    $res = file_get_contents('http://xat.com/web_gear/chat/BuyShortName.php', false, stream_context_create($stream));

    if (!$res) {
        return $bot->network->sendMessageAutoDetection($who, 'Cannot access page right now.', $type);
    }

    $res = explode('<ul data-localize=buy.rulesname>', $res)[1];


    if (strpos($res, 'Name is not allowed.') !== false) {
        return $bot->network->sendMessageAutoDetection($who, 'The shortname ' . $message[1] . ' is not allowed.', $type);
    }

    if (strpos($res, 'Sorry, name already taken') !== false) {
        if (strpos($res, '(1)' !== false)) {
            return $bot->network->sendMessageAutoDetection($who, 'The shortname ' . $message[1] . ' is taken but can be released via ticket.', $type);
        } else {
            return $bot->network->sendMessageAutoDetection($who, 'The shortname ' . $message[1] . ' is taken and cannot be released via ticket.', $type);
        }
    }

    if (preg_match('<input type="hidden" name="Xats" value="(.*?)">', $res, $matches)) {
        return $bot->network->sendMessageAutoDetection($who, $message[1] . ' costs ' . $matches[1] .' xats.', $type);
    }

    $bot->network->sendMessageAutoDetection($who, 'Unknown error.', $type);
};
