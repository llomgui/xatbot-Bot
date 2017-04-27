<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Command extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];
}
