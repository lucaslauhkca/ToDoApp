<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::where('user_id', Auth::id())->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create(['name' => $request->name, 'user_id' => Auth::id()]);
        return response()->json($category, 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json(Category::where('id', $id)->where('user_id', Auth::id())->firstOrFail());
    }

    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $category->update($request->only('name'));
        return response()->json($category);
    }

    public function destroy($id): JsonResponse
    {
        Category::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function tasks($category_id)
    {
        $category = Category::where('id', $category_id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($category->tasks);
    }
}
