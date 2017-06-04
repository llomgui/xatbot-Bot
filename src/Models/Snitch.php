<?php

namespace OceanProject\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Snitch extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['xatid', 'regname'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'snitchs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function snitchBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
