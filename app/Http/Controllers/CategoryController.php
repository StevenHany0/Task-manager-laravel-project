<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        $category = Category::create($request->only(['name']));
        return response()->json($category, 201);
    }

    public function getTasks($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $tasks = $category->tasks;
        return response()->json($tasks, 200);
    }
}
