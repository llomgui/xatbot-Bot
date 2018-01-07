<?php

namespace xatbot;

use xatbot\Models\Userinfo as UI;

class Userinfo
{
    public function __construct(int $xatid, string $regname, int $chatid, string $chatname, string $packet)
    {
        $user = UI::where('xatid', $xatid)->first();
        if ($user === null) {
            $ui = new UI;
            $ui->xatid    = $xatid;
            $ui->regname  = $regname;
            $ui->chatid   = $chatid;
            $ui->chatname = $chatname;
            $ui->packet   = $packet;
            $ui->optout   = false;
            $ui->save();
        } else {
            $user->regname  = $regname;
            $user->chatid   = $chatid;
            $user->chatname = $chatname;
            $user->packet   = $packet;
            $user->save();
        }
    }
}
