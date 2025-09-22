<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\SaveTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'user_id' => auth()->user()->id
        ]);

        if (! $task) {
            return response()->json([
                'message' => 'Error creating the task'
            ], 500);
        }

        return response()->json([
            'message' => 'Task successfully created',
            'task' => $task
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($task)
    {
        $task = Task::find($task);

        if (! $task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        if (auth()->user()->cannot('view', $task)) {
            return response()->json([
                'message' => 'You do not have permission to view this task'
            ], 403);
        }

        return TaskResource::make($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveTaskRequest $request, $task)
    {
        $task = Task::find($task);

        if (! $task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        if ($request->user()->cannot('update', $task)) {
            return response()->json([
                'message' => 'You do not have permission to update this task'
            ], 403);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Task successfully updated',
            'task' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($task)
    {
        $task = Task::find($task);

        if (! $task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        if (auth()->user()->cannot('delete', $task)) {
            return response()->json([
                'message' => 'You do not have permission to delete this task'
            ], 403);
        }

        $task->delete();

        return response()->noContent();
    }
}
