<?php

namespace App\Http\Controllers\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InfoController extends Controller
{
    public function index(Request $request)
    {
        \phpinfo();
        $content = 'I am test';

        return response($content);
    }
}
