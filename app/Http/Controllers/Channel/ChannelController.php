<?php

namespace App\Http\Controllers\Channel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel\Channel;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $content = Channel::with(['videos', 'user'])
            ->get();

        return response($content, 200);
    }

    public function show(Channel $channel)
    {
        $content = $channel
            ->load(['videos', 'user']);

        return response($content, 200);
    }
}
