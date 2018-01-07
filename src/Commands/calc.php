<?php

$calc = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'calc')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    unset($message[0]);
    $message = implode('', $message);

    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !calc [equation]', $type, true);
    }

    $expr = explode('|', $message);

    $stream = [];
    $stream['http']['method'] = 'POST';
    $stream['http']['header'] = 'Content-Type: application/x-www-form-urlencoded';
    $stream['http']['content'] = json_encode(['expr' => $expr]);
    $stream['http']['timeout'] = 1;
    
    $res = file_get_contents('http://api.mathjs.org/v1/', false, stream_context_create($stream));
    if (!$res) {
        /*
            TODO
            if site not reachable use preset calc code
        */
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.calc.cantsolve'),
            $type
        );
    }
    $json = json_decode($res);
    if ($json->error == null) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            '_' . $message . ' = ' .$json->result[count($json->result) - 1],
            $type
        );
    } else {
        return $bot->network->sendMessageAutoDetection($who, $json->error, $type, true);
    }
};
