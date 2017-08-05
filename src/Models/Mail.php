<?php

namespace OceanProject\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @property integer $id
 * @property integer $touser
 * @property integer $fromuser
 * @property string $message
 * @property boolean $read
 * @property boolean $store
 * @property string $created_at
 * @property string $updated_at
 */
class Mail extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mails';

    /**
     * @var array
     */
    protected $fillable = ['touser', 'fromuser', 'message', 'read', 'store', 'created_at', 'updated_at'];
}
