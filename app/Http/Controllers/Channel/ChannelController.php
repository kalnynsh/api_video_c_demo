<?php

namespace App\Http\Controllers\Channel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel\Channel;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $content = Channel::get();

        return response($content, 200);
    }

    public function show(Channel $channel)
    {
        return response($channel, 200);
    }
}
