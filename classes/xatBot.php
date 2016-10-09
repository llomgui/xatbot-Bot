<?php

require_once 'xatNetwork.php';

class Bot
{
    public $network;
    public $botData;
    public $chatInfo;
    public $users;
    public $started;
    public $minranks;
    public $alias;
    public $messageCount;
    public $done;

    public function __construct($botData)
    {
        $this->started  = time();
        
        $this->minranks = (!empty($botData['minranks'])) ? $botData['minranks'] : [];
        $this->alias = (!empty($botData['alias'])) ? $botData['alias'] : [];

        if (isset($botData['minranks'])) {
            unset($botData['minranks']);
        }
        
        if (isset($botData['alias'])) {
            unset($botData['alias']);
        }

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

    public function flagToRank($id)
    {
        if (!isset($this->users[$id])) {
            return 0;
        }

        if ($this->users[$id]->isMain()) {
            return 5;
        } else if ($this->users[$id]->isOwner()) {
            return 4;
        } else if ($this->users[$id]->isMod()) {
            return 3;
        } else if ($this->users[$id]->isMember()) {
            return 2;
        } else if ($this->users[$id]->isGuest()) {
            return 1;
        }
        else {
            return 0;
        }
    }

    public function stringToRank($string)
    {
        $string = strtolower($string);
        switch($string) {
            case 'main':
                $rank = 5;
                break;
            case 'owner':
                $rank = 4;
                break;
            case 'mod':
            case 'moderator':
                $rank = 3;
                break;
            case 'mem':
            case 'member':
                $rank = 2;
                break;
            case 'guest':
                $rank = 1;
                break;
            case 'banned':
                $rank = 0;
                break;
            default:
                $rank = 0;
        }

        return $rank;
    }

    public function minrank($id, $command)
    {
        if (!is_numeric($this->minranks[$command])) {
            $this->minranks[$command] = $this->stringToRank($this->minranks[$command]);
        }
        
        if ($this->flagToRank($id) >= $this->minranks[$command]) {
            return true;
        }

        return false;
    }

    public function secondsToTime($seconds) 
    {
        $dtF = new \DateTime('@0');

        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
}
