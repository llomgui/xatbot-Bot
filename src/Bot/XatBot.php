<?php

namespace OceanProject\Bot;

use Illuminate\Database\Capsule\Manager as Capsule;
use OceanProject\Models\Bot;

class XatBot
{
    public $network;
    public $data;
    public $chatInfo;
    public $users;
    public $started;
    public $minranks;
    public $aliases;
    public $responses;
    public $stafflist;
    public $badwords;
    public $botlangs;
    public $messageCount;
    public $isPremium;
    public $done;

    public function __construct(Bot $data)
    {
        $this->data      = $data;
        $this->started   = time();
        $this->aliases   = $this->setAliases();
        $this->minranks  = $this->setMinranks();
        $this->botlangs  = $this->setBotlangs();
        $this->badwords  = $this->setBadwords();
        $this->responses = $this->setResponses();
        $this->stafflist = $this->setStafflist();

        if ($this->data->premium > time() && $this->data->premiumfreeze == 1) {
            $this->isPremium = true;
        } else {
            $this->isPremium = false;
        }

        $this->network = new XatNetwork($this->data);
    }

    public function setMinranks()
    {
        $results = Capsule::table('commands')
                    ->leftJoin('bot_command_minrank', 'bot_command_minrank.command_id', '=', 'commands.id')
                    ->leftJoin('minranks', 'bot_command_minrank.minrank_id', '=', 'minranks.id')
                    ->where('bot_id', $this->data->id)
                    ->orWhere('bot_command_minrank.bot_id', '=', null)
                    ->select('commands.name', 'minranks.level', 'commands.default_level')
                    ->get()
                    ->toArray();

        for ($i = 0; $i < sizeof($results); $i++) {
            if (empty($results[$i]->level)) {
                $results[$i]->level = $results[$i]->default_level;
            }
        }

        return array_column($results, 'level', 'name');
    }

    public function setBotlangs()
    {
        $results = Capsule::table('botlang')
                    ->leftJoin('botlang_sentences', 'botlang.botlang_sentences_id', '=', 'botlang_sentences.id')
                    ->where('botlang.bot_id', $this->data->id)
                    ->orWhere('botlang.bot_id', '=', null)
                    ->select('botlang_sentences.name', 'botlang.value', 'botlang_sentences.default_value')
                    ->get()
                    ->toArray();

        for ($i = 0; $i < sizeof($results); $i++) {
            if (empty($results[$i]->value)) {
                $results[$i]->value = $results[$i]->default_value;
            }
        }

        return array_column($results, 'value', 'name');
    }

    public function setAliases()
    {
        $results = Capsule::table('aliases')
                ->where('bot_id', $this->data->id)
                ->select('aliases.command', 'aliases.alias')
                ->get()
                ->toArray();

        return array_column($results, 'command', 'alias');
    }

    public function setResponses()
    {
        $results = Capsule::table('responses')
                ->where('bot_id', $this->data->id)
                ->select('phrase', 'response')
                ->get()
                ->toArray();

        return array_column($results, 'response', 'phrase');
    }

    public function setStafflist()
    {
        $results = Capsule::table('staffs')
                ->join('minranks', 'staffs.minrank_id', '=', 'minranks.id')
                ->where('bot_id', $this->data->id)
                ->select('staffs.xatid', 'minranks.level')
                ->get()
                ->toArray();

        return array_column($results, 'level', 'xatid');
    }

    public function setBadwords()
    {
        $results = Capsule::table('badwords')
                ->where('bot_id', $this->data->id)
                ->select('badword', 'method', 'hours')
                ->get()
                ->toArray();

        $badwords = [];
        for ($i = 0; $i < sizeof($results); $i++) {
            $badwords[$i]['badword'] = $results[$i]->badword;
            $badwords[$i]['method']  = $results[$i]->method;
            $badwords[$i]['hours']   = $results[$i]->hours;
        }

        return $badwords;
    }

    public function botHasPower($id)
    {
        if (!$this->isPremium()) {
            return false;
        }

        if (!is_numeric($id)) {
            $powers = XatVariables::getPowers();
            foreach ($powers as $key => $value) {
                if ($value['name'] == $id) {
                    $id = $key;
                    break;
                }
            }
            if (!is_numeric($id)) {
                return false;
            }
        }
        $id    = (int)$id;
        $index = (int)($id / 32) + 4;
        $bit   = (int)($id % 32);

        return (isset($this->network->logininfo['d' . $index]) &&
            ($this->network->logininfo['d' . $index] & (1 << $bit)));
    }

    public function flagToRank($id)
    {
        if (!isset($this->users[$id])) {
            return 0;
        }

        if ($this->users[$id]->isMain()) {
            return 5;
        } elseif ($this->users[$id]->isOwner()) {
            return 4;
        } elseif ($this->users[$id]->isMod()) {
            return 3;
        } elseif ($this->users[$id]->isMember()) {
            return 2;
        } elseif ($this->users[$id]->isGuest()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function stringToRank($string)
    {
        $string = strtolower($string);
        switch ($string) {
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
        if (!isset($this->minranks[$command])) {
            echo 'No minrank for ' . strtoupper($command) . ' command.';
            return false;
        }

        if (in_array($id, XatVariables::getDevelopers())) {
            return true;
        }

        if (!is_numeric($this->minranks[$command])) {
            $this->minranks[$command] = $this->stringToRank($this->minranks[$command]);
        }
        
        if ($this->flagToRank($id) >= $this->minranks[$command]) {
            return true;
        }

        if (isset($this->stafflist[$id])) {
            if ($this->minranks[$command] <= $this->stafflist[$id]) {
                return true;
            }
        }

        return false;
    }
    
    public function botLang($name, $args = []): String
    {
        if (!isset($this->botlangs[$name])) {
            return 'Invalid sentence';
        }
        $args = array_map('strval', $args);
        $args = array_flip(preg_filter('/^/', '$', array_flip($args)));
        $response = str_replace(array_keys($args), array_values($args), $this->botlangs[$name]);
        return is_string($response) ? htmlspecialchars_decode($response) : 'Invalid sentence';
    }

    public function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');

        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    public function stribet($inputstr, $deliLeft, $deliRight)
    {
        $posLeft = stripos($inputstr, $deliLeft) + strlen($deliLeft);
        $posRight = stripos($inputstr, $deliRight, $posLeft);
        return substr($inputstr, $posLeft, $posRight - $posLeft);
    }

    public function getChatName($param1)
    {
        $url = 'http://xat.com/xat' . $param1;
        $fgc = file_get_contents($url);
        return $this->stribet($fgc, "uname='", "';");
    }

    public function refresh()
    {
        $bot = Bot::find($this->data->id);
        $this->__construct($bot);
    }

    public function sec2hms($sec, $padHours = false)
    {
        $hms = '';
        $days = intval($sec/86400);
        $hms .= (($padHours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' : $days . ' days, ');
        $sec-= ($days * 86400);
        $hours = intval(intval($sec) / 3600);
        $hms .= (($padHours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' : $hours . ' hours, ');
        $minutes = intval(($sec / 60) % 60);
        $hms .= str_pad($minutes, 2, '0', STR_PAD_LEFT) . ' minutes, ';
        $seconds = intval($sec % 60);
        $hms .= str_pad($seconds, 2, '0', STR_PAD_LEFT) . ' seconds';
        return $hms;
    }
}
