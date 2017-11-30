<?php

$handlecustomcommands = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();
    $customcommand = substr($message[0], 1);
    $bool = false;

    if (!empty($bot->customcommands)) {
        foreach ($bot->customcommands as $cc) {
            if ($customcommand == $cc['command']) {
                $bool = true;
                if ($bot->flagToRank($who) < $cc['level']) {
                    if (isset($bot->stafflist[$who]) && $cc['level'] <= $bot->stafflist[$who]) {
                    } else {
                        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
                    }
                }

                $randomuser = [];
                foreach ($bot->users as $id => $object) {
                    if (is_object($object)) {
                        $randomuser[] = $object;
                    }
                }

                $search[] = '{randomname}';
                $replace[] = $randomuser[rand(0, sizeof($randomuser) - 1)]->getNick();
                $search[] = '{name}';
                $replace[] = $bot->users[$who]->getNick();
                $search[] = '{status}';
                $replace[] = $bot->users[$who]->getStatus();
                $search[] = '{regname}';
                $replace[] = $bot->users[$who]->getRegname() ?? $bot->users[$who]->getID();
                $search[] = '{users}';
                $replace[] = sizeof($bot->users);
                $search[] = '{cmdcode}';
                $replace[] = $bot->data->customcommand;
                $search[] = '{id}';
                $replace[] = $bot->users[$who]->getID();
                
                $response = str_replace($search, $replace, $cc['response']);
                return $bot->network->sendMessageAutoDetection($who, $response, $type);
            }
        }
    }

    if (!$bool) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.doesnotexist'), $type);
    }
};
