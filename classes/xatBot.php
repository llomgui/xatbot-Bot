<?php

require_once 'xatNetwork.php';

class Bot
{
    public $network;
    public $botData;
    public $chatInfo;
    public $users;

    public function __construct($botData)
    {
        foreach ($botData as $key => $val) {
            $this->botData[$key] = htmlspecialchars_decode($val);
        }

        $this->network = new Network($this->botData);
    }

    public function botHasPower($id)
    {
        $id    = (int)$id;
        $index = (int)($id / 32) + 4;
        $bit   = (int)($id % 32);

        return (isset($this->network->logininfo['d' . $index]) && ($this->network->logininfo['d' . $index] & (1 << $bit)));
    }
}
