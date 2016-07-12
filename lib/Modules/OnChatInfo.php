<?php

namespace Ocean\Xat\Modules;

use Ocean\Xat\API\ActionAPI;

class OnChatInfo
{
    public function __invoke($array)
    {
        $bot = ActionAPI::getBot();

        $rankA = [0 => 'Guest', 1 => 'Main', 2 => 'Moderator', 3 => 'Member', 4 => 'Owner', 5 => 'Guest'];
        $info  = explode(';=', $array['b']);

        $bot->chatInfo['background']   = explode('#', $info[0])[0];
        $bot->chatInfo['tabbedChat']   = $info[1];
        $bot->chatInfo['tabbedChatID'] = $info[2];
        $bot->chatInfo['language']     = $info[3] ?? 'en';
        $bot->chatInfo['radio']        = str_replace('http://', '', $info[4]);
        $bot->chatInfo['buttons']      = $info[5] ?? 'None';
        $bot->chatInfo['bot']          = $array['B'] ?? $this->botData['id'];
        $bot->chatInfo['rank']         = isset($array['r']) &&
                                            isset($rankA[$array['r']]) ? $rankA[$array['r']] : 'Guest';
    }
}
