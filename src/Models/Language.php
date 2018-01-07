<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Language extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'code'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';
}
