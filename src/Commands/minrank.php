<?php

use OceanProject\Utilities;
use OceanProject\Models\Minrank;
use OceanProject\Models\Command;
use Illuminate\Database\Capsule\Manager as Capsule;

$minrank = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'minrank')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !minrank [command] [guest/member/moderator/owner] (If no rank, minrank will be displayed)',
            $type
        );
    }

    if (isset($message[1]) && !empty($message[1])) {
        $command = strtolower($message[1]);
        if (!isset($message[2])) {
            // command not found
            if (!isset($bot->minranks[$command])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'This command is not a command!',
                    $type
                );
            }
            
            // fetching minrank name by its level
            $minrankName = Minrank::where('level', $bot->minranks[$command])->first();
            return $bot->network->sendMessageAutoDetection(
                $who,
                'The minrank for ' . strtoupper($message[1]) . ' is ' . strtoupper($minrankName->name) . '.',
                $type
            );
        } else {
            $newMinrank = strtolower($message[2]);
            
            // because we can do it!
            if ($newMinrank == "botowner") {
                $newMinrank = "Bot Owner";
            }
            
            // command not found
            if (!isset($bot->minranks[$command])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'This command is not a command!',
                    $type
                );
            }
            
            // Checking if minrank is valid
            if (!in_array(ucfirst($newMinrank), Minrank::pluck('name')->toArray())) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'The minrank is not valid!',
                    $type
                );
            }
            
            // Check if the minrank requested is same than db..
            $minrankName = Minrank::where('level', $bot->minranks[$command])->first();
            if (strtolower($minrankName->name) == $newMinrank) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'The minrank is already set to ' . strtoupper($newMinrank) . '.',
                    $type
                );
            }
            
            // Get minrank id
            $minrank = Minrank::where('name', ucfirst($newMinrank))->first();
            
            // Get command ID
            $command = Command::where('name', $command)->first();

            $req = Capsule::table('bot_command_minrank')
                        ->where([
                            ['command_id', '=', $command->id],
                            ['bot_id', '=', $bot->data->id]
                        ])
                        ->get()
                        ->toArray();

            if (sizeof($req) > 0) {
                $bot->data->commands()->updateExistingPivot($command->id, ['minrank_id' => $minrank->id]);
            } else {
                $bot->data->commands()->save($command, ['minrank_id' => $minrank->id]);
            }
            
            // Refresh new minranks
            $bot->minranks = $bot->setMinranks();
            
            return $bot->network->sendMessageAutoDetection(
                $who,
                'The new minrank for ' . strtoupper($message[1]) . ' is ' . strtoupper($newMinrank) . '.',
                $type
            );
        }
    }
};
