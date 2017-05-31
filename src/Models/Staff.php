<?php

namespace OceanProject\Bot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Staff extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['xatid', 'regname', 'minrank_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'staffs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function staffMinrank()
    {
        return $this->hasOne(Minrank::class, 'id', 'minrank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function staffBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
