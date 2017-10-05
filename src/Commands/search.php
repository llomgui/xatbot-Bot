<?php

$search = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'search')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !search [word]', $type);
    }

    $r_regname = '\[([^\]]+)\]</font>';
    $r_message = '>([^<]+)</a></b>';
    $r_link    = '>(xat.com/[^<]+)<';
    $regex     = '!' . $r_regname . '.+' . $r_message . '.+' . $r_link . '!Us';

    $a = [];
    $a['http']['method']  = 'POST';
    $a['http']['header']  = 'Content-Type: application/x-www-form-urlencoded';
    $a['http']['content'] = 'search='.$message[1];

    $fgc = file_get_contents('https://xat.com/web_gear/chat/search.php', false, stream_context_create($a));
    preg_match_all($regex, $fgc, $matches);

    unset($matches[0]);
    $array = [];

    foreach ($matches as $match) {
        foreach ($match as $key => $val) {
            $array[$key][] = $val;
        }
    }

    if (sizeof($array) >= 3) {
        for ($i = 0; $i < 3; $i++) {
            $newMessage = '['.$array[$i][0] . '] - ' . $array[$i][1]. ' at ' . $array[$i][2];

            if (sizeof($bot->packetsinqueue) > 0) {
                $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 2000] = [
                    'who' => $who,
                    'message' => $newMessage,
                    'type' => $type
                ];
            } else {
                $bot->packetsinqueue[round(microtime(true) * 1000) + 2000] = [
                    'who' => $who,
                    'message' => $newMessage,
                    'type' => $type
                ];
            }
        }
    } elseif (sizeof($array) > 0) {
        for ($i = 0; $i < sizeof($array); $i++) {
            $newMessage = '['.$array[$i][0] . '] - ' . $array[$i][1] . ' at ' . $array[$i][2];

            if (sizeof($bot->packetsinqueue) > 0) {
                $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 2000] = [
                    'who' => $who,
                    'message' => $newMessage,
                    'type' => $type
                ];
            } else {
                $bot->packetsinqueue[round(microtime(true) * 1000) + 2000] = [
                    'who' => $who,
                    'message' => $newMessage,
                    'type' => $type
                ];
            }
        }
    } else {
        $bot->network->sendMessageAutoDetection($who, 'Sorry, I don\'t have any message about this.', $type);
    }
};
