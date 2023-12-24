<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $content = User::get();

        return response($content, 200);
    }

    public function show(User $user)
    {
        return response($user, 200);
    }
}

