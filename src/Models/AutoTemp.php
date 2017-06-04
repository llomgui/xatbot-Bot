<?php

namespace OceanProject\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class AutoTemp extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['xatid', 'regname', 'hours'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'autotemps';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function autotempBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
