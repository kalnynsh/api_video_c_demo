<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $content = Category::get();

        return response($content, 200);
    }

    public function show(Category $category)
    {
        return response($category, 200);
    }
}
