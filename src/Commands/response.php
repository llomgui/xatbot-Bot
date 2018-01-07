<?php

use OceanProject\Utilities;
use OceanProject\Models\Response;

$response = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'response')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1]) || !in_array($message[1], ['add', 'remove', 'rm'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !response [add/remove] [response] [answer]',
            $type
        );
    }

    switch (strtolower($message[1])) {
        case 'add':
            $message = implode(' ', $message);
            preg_match_all("/\[[^\]]*\]/", $message, $test);
            if (!isset($test[0][0]) || !isset($test[0][1])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !response add [response] [answer]',
                    $type
                );
            }

            $response = str_replace(['[', ']'], '', $test[0][0]);
            $answer = str_replace(['[', ']'], '', $test[0][1]);

            if (empty($response) || empty($answer)) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !response add [response] [answer]',
                    $type
                );
            }

            foreach ($bot->responses as $responses => $answers) {
                if ($responses == strtolower($response)) {
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.response.inuse'),
                        $type
                    );
                }
            }

            $newResponse = new Response;
            $newResponse->bot_id = $bot->data->id;
            $newResponse->phrase = $response;
            $newResponse->response = $answer;
            $newResponse->save();

            $bot->responses = $bot->setResponses();

            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.response.added'),
                $type
            );
        break;
        case 'rm':
        case 'remove':
            $message = implode(' ', $message);
            preg_match_all("/\[[^\]]*\]/", $message, $test);
            if (!isset($test[0][0])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !response remove [response]',
                    $type
                );
            }

            $response = str_replace(['[', ']'], '', $test[0][0]);
            foreach ($bot->responses as $r => $a) {
                if ($r == strtolower($response)) {
                    Response::where([
                        ['phrase', '=', strtolower($response)],
                        ['bot_id', '=', $bot->data->id]
                    ])->delete();

                    $bot->responses = $bot->setResponses();

                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.response.removed'),
                        $type
                    );
                }
            }
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.response.notfound'),
                $type
            );
        break;
    }
};
