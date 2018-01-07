<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @property integer $xatid
 * @property string $regname
 * @property integer $chatid
 * @property string $chatname
 * @property string $packet
 * @property boolean $optout
 * @property string $created_at
 * @property string $updated_at
 */
class Userinfo extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userinfo';

    /**
     * @var array
     */
    protected $fillable = ['xatid', 'regname', 'chatid', 'chatname', 'packet', 'optout'];
}
