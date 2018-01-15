<?php

use xatbot\Models\CustomCommand;
use xatbot\Models\Minrank;

$customcommand = function (int $who, array $message, int $type) {

    $bot = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'customcommand')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])
        || !in_array(strtolower($message[1]), ['add', 'remove', 'rm', 'list', 'ls'])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !customcommand [add/remove] [newcommand] [rank] [message]',
            $type
        );
    }
    
    switch (strtolower($message[1])) {
        case 'add':
            if (!isset($message[2], $message[3], $message[4])  || empty($message[2]) || empty($message[3])
                || empty($message[4])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !customcommand add [newcommand] [rank] [message]',
                    $type
                );
            }
            $command = ctype_alnum($message[2][0]) ? $message[2] : substr($message[2], 1);
            $command = strtolower($command);
            foreach ($bot->customcommands as $cc) {
                if ($command == $cc['command']) {
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.customcommand.alreadyadded'),
                        $type
                    );
                }
            }

            if (isset($bot->minranks[$command])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('cmd.customcommand.alreadycommand'),
                    $type
                );
            }
            
            $rank = strtolower($message[3]);
            if ($rank == "botowner") {
                $rank = "Bot Owner";
            }
            
            if (!in_array(ucfirst($rank), Minrank::pluck('name')->toArray())) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    $bot->botlang('minrank.minranknotvalid'),
                    $type
                );
            }
            
            $minrankID = Minrank::where('name', ucfirst($rank))->first();
            
            $customCmd = new CustomCommand;
            $customCmd->bot_id = $bot->data->id;
            $customCmd->command = $command;
            $customCmd->response = implode(' ', array_slice($message, 4));
            $customCmd->minrank_id = (int)$minrankID->id;
            $customCmd->save();
            
            $bot->customcommands = $bot->setCustomCommands();
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.customcommand.added', [$command]),
                $type
            );
            break;
        case 'remove':
        case 'rm':
            if (!isset($message[2]) || empty($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !customcommand remove [customcommand]',
                    $type
                );
            }
            $command = ctype_alnum($message[2][0]) ? $message[2] : substr($message[2], 1);
            foreach ($bot->customcommands as $cc) {
                if (strtolower($command) == $cc['command']) {
                    CustomCommand::where([
                      ['command', '=', $command],
                      ['bot_id', '=', $bot->data->id]
                    ])->delete();
                    
                    $bot->customcommands = $bot->setCustomCommands();
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        $bot->botlang('cmd.customcommand.removed', [$command]),
                        $type
                    );
                }
            }
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.customcommand.notfound'),
                $type
            );
            break;
        case 'list':
        case 'ls':
            $cmdList = [];
            foreach ($bot->customcommands as $cc) {
                $cmdList[] = $cc['command'];
            }
            $cmdsList = sizeof($cmdList) > 0 ? implode(', ', $cmdList) : 'N/A';
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.customcommand.currentlist', [$cmdsList]),
                $type
            );
            break;
    }
};
