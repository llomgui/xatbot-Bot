<?php

$onChatInfo = function ($array) {
    
    $bot = actionAPI::getBot();
    $info = explode(';=', $array['b']);
    
    $bot->chatInfo['background'] = explode('#', $info[0])[0];
    $bot->chatInfo['tabbedChat'] = $info[1];
    $bot->chatInfo['tabbedChatID'] = $info[2];
    $bot->chatInfo['language'] = $info[3];
    $bot->chatInfo['radio'] = str_replace('http://', '', $info[4]);
    $bot->chatInfo['buttons'] = $info[5] ?? 'None';// <3 PHP7
    $bot->chatInfo['bot'] = $array['B'] ?? $this->botData['id'];// <3 PHP7

    
    $rankA = [0 => 'Guest', 1 => 'Main', 2 => 'Moderator', 3 => 'Member', 4 => 'Owner', 5 => 'Guest'];
    $bot->chatInfo['rank'] = isset($array['r']) && isset($rankA[$array['r']]) ? $rankA[$array['r']] : 'Guest';
};
