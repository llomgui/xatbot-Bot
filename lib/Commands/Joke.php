<?php

namespace Ocean\Xat\Commands;

use Ocean\Xat\API\ActionAPI;

class Joke
{
    public function __invoke($who, $message, $type)
    {
        $bot = ActionAPI::getBot();

        $stream = stream_context_create(['http'=> ['timeout' => 1]]);
        $page = file_get_contents('http://www.jokesclean.com/OneLiner/Random/', false, $stream);
        if (!$page) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                'I am unable to grab a joke at this moment, please try again later.',
                $type
            );
        }

        $joke = explode('<p class="c"> <font size="+2">', $page)[1];
        $joke = explode('</font></p>', $joke)[0];
        $bot->network->sendMessageAutoDetection($who, $joke, $type);
    }
}
