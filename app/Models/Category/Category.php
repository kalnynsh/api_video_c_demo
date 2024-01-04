<?php

namespace App\Models\Category;

use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function videos()
    {
        return $this->belongsToMany(
            Video::class,
            'category_video',
            'category_id',
            'video_id'
        );
    }
}
