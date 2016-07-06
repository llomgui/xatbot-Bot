<?php

$wikipedia = function ($who, $message, $type) {
    $bot = actionAPI::getBot();
    
    unset($message[0]);
    $message = implode(' ', $message);
    
    if (empty($message)) {
        return $bot->network->sendMessageAutoDetection($who, 'You\'re not searching for anything (confused#)', $type);
    }
    $stream = stream_context_create(['http'=> ['timeout' => 1]]);
    $page = file_get_contents('http://en.wikipedia.org/w/api.php?action=opensearch&search=' . urlencode($message) . '&format=xml&limit=1', false, $stream);
    if (!$page) {
        return $bot->network->sendMessageAutoDetection($who, 'I can\'t reach wikipedia.org at this monent, please try again later.', $type);
    }
    
    $xml = simplexml_load_string($page);
    if ((string)$xml->Section->Item->Description) {
        $wiki = "Wikipedia page: http://en.wikipedia.org/wiki/" . (string)$xml->Section->Item->Text;
    } else {
        $wiki = "Wikipedia page could not be found.";
    }
    $bot->network->sendMessageAutoDetection($who, $wiki, $type);
};
