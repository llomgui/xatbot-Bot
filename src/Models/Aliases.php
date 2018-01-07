<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Aliases extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['command', 'alias'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'aliases';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function aliasesBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
