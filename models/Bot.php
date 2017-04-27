<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Bot extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = ['bot_status_id', 'creator_user_id', 'server_id', 'premium', 'chatid', 'chatname', 'chatpw', 'nickname', 'avatar', 'homepage', 'status', 'pcback', 'autowelcome', 'ticklemessage', 'maxkick', 'maxkickban', 'maxflood', 'maxchar', 'maxsmilies', 'automessage', 'automessagetime', 'autorestart'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function commands()
    {
        return $this->belongsToMany(Command::class, 'bot_command_minrank', 'bot_id', 'command_id')->withPivot('minrank_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function minranks()
    {
        return $this->belongsToMany(Minrank::class, 'bot_command_minrank', 'bot_id', 'minrank_id')->withPivot('command_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function bot_status()
    {
        return $this->hasOne(BotStatus::class, 'id', 'bot_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function autotemps()
    {
        return $this->hasMany(AutoTemp::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function snitchs()
    {
        return $this->hasMany(Snitch::class);
    }
}
