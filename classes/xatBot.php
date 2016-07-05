<?php

require_once 'xatNetwork.php';

class Bot
{
    public $network;
    public $botData;
    public $users;

    public function __construct($botData)
    {
        foreach ($botData as $key => $val) {
            $this->botData[$key] = htmlspecialchars_decode($val);
        }

        $this->network = new Network($this->botData);
    }
}
