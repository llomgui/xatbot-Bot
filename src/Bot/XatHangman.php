<?php

namespace xatbot\Bot;

use xatbot\Utilities;

class XatHangman
{
    private $defaultPacket;
    private $alphabet;
    private $hangman;
    private $bot;
    private $user;
    private $word;
    private $lives;
    private $correct;

    public function __construct($bot, $word, $uid)
    {
        $this->bot = $bot;
        $this->user = $bot->users[$uid];
        $this->word = $word;
        $this->defaultPacket = [
            'i' => 60002,
            'u' => XatVariables::getXatid(),
            'd' => $this->user->getID()
        ];
        $this->alphabet = range('a', 'z');
        $this->hangman = [
            'base,line,162,300,262,300',
            'post,line,162,300,162,150',
            'arm,line,162,150,212,150',
            'noose,line,212,150,212,170',
            'head,circle,212,180,10',
            'body,line,212,190,212,230',
            'rarm,line,212,200,182,210',
            'lram,line,212,200,242,210',
            'rleg,line,212,230,182,260',
            'lleg,line,212,230,242,260'
        ];
        $this->lives = \count($this->hangman);
        $this->correct = 0;

        $this->connection();
        $this->clear();
        $this->setTitle();
        $this->setWord();
        $this->setStatus();
        $this->setAlphabet();
    }

    public function process($letter)
    {
        if ($this->correct == strlen($this->word) || $this->lives == 0) {
            return;
        }

        $this->removeLetter($letter);

        $positions = Utilities::strposRecursive($this->word, $letter);
        if (\count($positions) == 0) {
            $this->lives--;
            $this->drawNextLine();
            $this->setStatus();
            if ($this->lives == 0) {
                $this->setWord();
            }
        } else {
            foreach ($positions as $position) {
                $this->setWord($position);
                $this->correct++;
            }

            if ($this->correct == strlen($this->word)) {
                $this->setStatus();
            }
        }
    }

    private function connection()
    {
        $text = '0,bot,' . XatVariables::getXatid();
        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function clear()
    {
        $text = 'clear';
        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function setTitle()
    {
        $text = 'title,text,Hangman ' . $this->user->getID() . ',0,0,425,50';
        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function setStatus()
    {
        if ($this->lives > 0 && ($this->correct != strlen($this->word))) {
            $text = 'status,text,You have ' . $this->lives . ' lives!,0,325,425,50';
        } elseif ($this->correct == strlen($this->word)) {
            $text = 'status,text,Winner with ' . $this->lives . ' lives!,0,325,425,50';
        } else {
            $text = 'status,text,Game over,0,325,425,50';
        }

        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function setWord($position = null)
    {
        $text = '';
        if ($this->lives > 0 && is_null($position)) {
            for ($i = 0; $i < \strlen($this->word); $i++) {
                $text .= $i . ',text,_,' . ($i * 40 + (212 - 10 * 20)) . ',80,40,40;';
            }
        } elseif ($this->lives == 0) {
            for ($i = 0; $i < \strlen($this->word); $i++) {
                $text .= $i . ',text,' . $this->word[$i] . ',' . ($i * 40 + (212 - 10 * 20)) . ',80,40,40;';
            }
        } else {
            $text = $position . ',text,' . $this->word[$position] . ',' . ($position * 40 + (212 - 10 * 20)) .
                ',80,40,40';
        }

        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function setAlphabet()
    {
        $text = '';
        for ($i = 0; $i < \count($this->alphabet); $i++) {
            $text .= $this->alphabet[$i] . ',button,' . $this->alphabet[$i] . ',' . (50*($i%7)+40) . ',' .
                ((($i-$i%7)/7)*50+400) . ',40,40;';
        }

        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }

    private function removeLetter($letter)
    {
        $this->bot->network->write('x', $this->defaultPacket + ['t' => $letter]);
    }

    private function drawNextLine()
    {
        $text = $this->hangman[(\count($this->hangman) - $this->lives) - 1];
        $this->bot->network->write('x', $this->defaultPacket + ['t' => $text]);
    }
}
