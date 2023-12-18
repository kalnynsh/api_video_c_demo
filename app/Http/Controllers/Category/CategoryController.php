<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $content = \json_encode([
            "All",
            "Trucks",
            "Tools",
        ]);

        return response($content, 200);
    }
}
