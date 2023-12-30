<?php

namespace App\Models\Video;

use App\Models\Channel\Channel;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function channel ()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_video',
            'category_id',
            'video_id'
        );
    }
}
