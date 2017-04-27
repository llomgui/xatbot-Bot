<?php

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
    public function staff_minrank()
    {
        return $this->hasOne(Minrank::class, 'id', 'minrank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function staff_bot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
