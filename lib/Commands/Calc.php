<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Calc
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        unset($message[0]);
        $message = implode('', $message);

        if (empty($message)) {
            return $bot->network->sendMessageAutoDetection($who, 'Usage: !calc [equation]', $type, true);
        }

        $expr = explode('|', $message);

        $stream = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => json_encode(['expr' => $expr]),
                'timeout' => 1
            ]
        ];
        $res = file_get_contents('http://api.mathjs.org/v1/', false, stream_context_create($stream));
        if (!$res) {
            /*
                TODO
                if site not reachable use preset calc code
            */
            return $bot->network->sendMessageAutoDetection(
                $who,
                'Sorry i can\'t solve any equation\'s at this time, please try again later.',
                $type
            );
        }
        $json = json_decode($res);
        if ($json->error == null) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                '_' . $message . ' = ' . $json->result[count($json->result) - 1],
                $type
            );
        } else {
            return $bot->network->sendMessageAutoDetection($who, $json->error, $type, true);
        }
    }
}
