<?php

use Ocean\Bot\API\ActionAPI;

$eightball = function ($who, $message, $type) {

    $bot = ActionAPI::getBot();

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection($who, "Usage: !eightball [question]", $type, true);
    }

    /*
        TODO "Yes" or "No" questions only?
    */

    $answers = ["Signs point to yes.", "Yes.", "Reply hazy, try again.", "Without a doubt.", "My sources say no.", "As I see it, yes.", "You may rely on it.", "Concentrate and ask again.", "Outlook not so good.", "It is decidedly so.", "Better not tell you now.", "Very doubtful.", "Yes - definitely.", "It is certain.", "Cannot predict now.", "Most likely.", "Ask again later.", "My reply is no.", "Outlook good.", "Don't count on it."];

    $bot->network->sendMessageAutoDetection($who, $answers[array_rand($answers)], $type);
};
