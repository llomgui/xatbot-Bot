<?php
$pc = function (int $who, array $message, int $type) {

    $bot = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'pc')) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('not.enough.rank'),
            $type
        );
    }

    if (empty($message[1]) || empty($message[2])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !pc [regname/xatid] [message]',
            $type
        );
    }

    if ((is_numeric($message[1])) && (isset($bot->users[$message[1]]))) {
        $user = $bot->users[$message[1]];
    } else {
        foreach ($bot->users as $id => $object) {
            if (is_object($object)) {
                if (strtolower($object->getRegname()) == strtolower($message[1])) {
                    $user = $object;
                    break;
                }
            }
        }
    }

    if (!(isset($user))) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('user.not.here'),
            $type
        );
    }

    $message = implode(' ', array_slice($message, 2));

    $bot->network->sendPrivateConversation(
        $user->getID(),
        $message ?? 'Hello'
    );

    $bot->network->sendMessageAutoDetection($who, $bot->botlang('cmd.pc.gotpc'), $type);
};
