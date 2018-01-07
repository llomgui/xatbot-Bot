<?php

namespace xatbot\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'xatid', 'regname', 'ip', 'password', 'language_id', 'share_key', 'spotify'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'spotify' => 'array',
    ];

    protected $primaryKey = 'id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function bots()
    {
        return $this->belongsToMany(Bot::class)->orderBy('bot_id', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function language()
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function botsCreated()
    {
        return $this->hasMany(Bot::class, 'creator_user_id');
    }

    public function hasBot($botid)
    {
        return $this->bots()->where('bot_id', $botid)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
