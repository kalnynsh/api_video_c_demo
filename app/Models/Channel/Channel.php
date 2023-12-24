<?php

namespace App\Models\Channel;

use App\Models\User\User;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'name',
    ];

    public function videos()
    {
        return $this->hasMany(Video::class, 'channel_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
