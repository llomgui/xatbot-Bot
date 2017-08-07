<?php

namespace OceanProject\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AutoBan extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['xatid', 'regname', 'hours', 'method'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'autobans';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function autobanBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
