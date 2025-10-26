<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(){
        $tasks = Auth::user()->tasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        return response()->json($tasks,200);
    }
     public function getAllTasks(){
        $tasks = Task::orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        return response()->json($tasks,200);
    }
    public function show($id){
        $task = Task::findOrFail($id);
        return response()->json($task, 200);
    }

    public function store(StoreTaskRequest $request){
        $validated = $request->validated();
        $validated['user_id']=$request->user()->id;
        $task = Task::create($validated);
        return response()->json($task,201);

    }

    public function update(UpdateTaskRequest $request,$id){
        $task = Task::findOrFail($id);
        // $task->update($request->all());
        // $task->update($request->only(['title', 'description', 'priority']));
        if($task->user_id !== $request->user()->id){
            return response()->json(['message'=>'Unauthorized'],403);
        }
        $task->update($request->validated());
        return response()->json($task, 200);
    }

    public function destroy($id){
        $task=Task::findOrFail($id);
        $task->delete();
        return response()->json(null,204);
    }

    public function getUser($id){
        $tasks = Task::findOrFail($id)->user;
        return response()->json($tasks,200);
    }

    public function addCategory(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->categories()->attach([$request->category_id]);
        return response()->json('Categories added successfully', 200);
    }

    public function getCategories($taskId)
    {
        $task = Task::findOrFail($taskId);
        $categories = $task->categories;
        return response()->json($categories, 200);
    }

    public function addFavorite($taskId)
    {
        Task::findOrFail($taskId);
        Auth::user()->favoriteTasks()->syncWithoutDetaching([$taskId]);
        return response()->json(['message' => 'Task added to favorites'], 200);

    }

    public function removeFavorite($taskId){
        Task::findOrFail($taskId);
        Auth::user()->favoriteTasks()->detach($taskId);
        return response()->json(['message'=>'Task removed from favorites'],200);
    }

    public function getFavoriteTasks(){
        $tasks = Auth::user()->favoriteTasks()->orderByRaw('FIELD(priority, "high", "medium", "low")')->get();
        return response()->json($tasks,200);
    }


}
