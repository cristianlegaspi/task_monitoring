<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskBoardController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/update-task-status', function () {
    // Get the task ID and new status from the request
    $taskId = request('id');
    $status = request('status');

    // Find the task and update its status
    $task = Task::findOrFail($taskId);
    $task->status = $status;
    $task->save();

    // Return a JSON response with the updated task data
    return response()->json($task);
});
