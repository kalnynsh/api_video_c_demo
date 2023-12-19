<?php

namespace App\Http\Controllers\Video;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video\Video;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $content = Video::get();

        return response($content, 200);
    }

    public function show(Video $video)
    {
        return response($video, 200);
    }
}
