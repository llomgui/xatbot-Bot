<?php

$listsmilies = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'listsmilies')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, 'Usage: !listsmilies [power/latest]', $type, true);
    }

    $powers = xatbot\Bot\XatVariables::getPowers();

    if (strtolower($message[1]) == 'latest') {
        $message[1] = end($powers)['name'];
    }

    $message[1] = str_replace(['(', ')'], '', $message[1]);
    $exist = false;
    foreach ($powers as $id => $array) {
        if ($array['name'] == strtolower($message[1]) || $id == $message[1]) {
            $exist = true;
            break;
        }
    }

    if ($exist) {
        if (!empty($array['smilies'])) {
            $topsh = $array['smilies'];

            if (count($topsh) > 10) {
                if (isset($message[2]) && strtolower($message[2]) == 'all') {
                    $nbMessages = ceil(sizeof($topsh) / 10);

                    if (sizeof($bot->packetsinqueue) > 0) {
                        $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 1000] = [
                            'who' => $who,
                            'message' => '('.implode('#)(', array_slice($topsh, 0, 10)).'#)',
                            'type' => $type
                        ];
                    } else {
                        $bot->packetsinqueue[round(microtime(true) * 1000) + 1000] = [
                            'who' => $who,
                            'message' => '('.implode('#)(', array_slice($topsh, 0, 10)).'#)',
                            'type' => $type
                        ];
                    }

                    for ($i = 10; $i < ($nbMessages * 10); $i += 10) {
                        if (sizeof($bot->packetsinqueue) > 0) {
                            $bot->packetsinqueue[max(array_keys($bot->packetsinqueue)) + 1000] = [
                                'who' => $who,
                                'message' => '('.implode('#)(', array_slice($topsh, $i, 10)).'#)',
                                'type' => $type
                            ];
                        } else {
                            $bot->packetsinqueue[round(microtime(true) * 1000) + 1000] = [
                                'who' => $who,
                                'message' => '('.implode('#)(', array_slice($topsh, $i, 10)).'#)',
                                'type' => $type
                            ];
                        }
                    }
                    return;
                } else {
                    $implode = $bot->botHasPower($id) ?
                        '('.implode('#)(', array_slice($topsh, 0, 10)).'#)' :
                        implode(' ', array_slice($topsh, 0, 10));
                    $implode .= implode(' ', array_slice($topsh, 10));
                }
            } else {
                $implode = $bot->botHasPower($id) ? '(' . implode('#)(', $topsh) . '#)' : implode(' ', $topsh);
            }

            $bot->network->sendMessageAutoDetection(
                $who,
                ucfirst($array['name']) . '\'s ' . count($topsh) . ' smilies: ' . $implode . '.',
                $type
            );
        } else {
            $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.listsmilies.nosmilies', [ucfirst($array['name'])]),
                $type
            );
        }
    } else {
        $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.listsmilies.notpower', [$message[1]]), $type);
    }
};
