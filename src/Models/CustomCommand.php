<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CustomCommand extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['command', 'response', 'minrank_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customcommands';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function customcommandBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
