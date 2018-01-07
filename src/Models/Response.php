<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Response extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['phrase', 'response'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'responses';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function responseBot()
    {
        return $this->hasOne(Bot::class, 'id', 'bot_id');
    }
}
