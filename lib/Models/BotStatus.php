<?php

namespace OceanProject\Bot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BotStatus extends Eloquent
{
	/**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_statuses';
}