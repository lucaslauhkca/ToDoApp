<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Task::where('user_id', Auth::id())->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['title' => 'required|string|max:255', 'category_id' => 'required|exists:categories,id']);
        $task = Task::create(['title' => $request->title, 'description' => $request->description, 'category_id' => $request->category_id, 'user_id' => Auth::id()]);
        return response()->json($task, 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json(Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail());
    }

    public function update(Request $request, $id): JsonResponse
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->update($request->only('title', 'description', 'category_id'));
        return response()->json($task);
    }

    public function destroy($id): JsonResponse
    {
        Task::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
