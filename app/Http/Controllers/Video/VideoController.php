<?php

namespace App\Http\Controllers\Video;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Video\Video;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $content = Video::with('channel')->get();

        return response($content, 200);
    }

    public function show(Video $video)
    {
        return response($video->load('channel'), 200);
    }
}
