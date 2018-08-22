<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserEvents extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = [
        'xatid', 'rank', 'chatid',
        'chatname', 'amount_commands', 'amount_bans', 'amount_kicks',
        'amount_messages', 'amount_ranks', 'connected_at',
        'left_at', 'created_at', 'updated_at'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_events';
}
