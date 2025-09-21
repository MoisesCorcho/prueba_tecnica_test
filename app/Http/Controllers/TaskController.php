<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
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
                'message' => 'Error al crear la tarea'
            ], 500);
        }

        return response()->json([
            'message' => 'Tarea creada correctamente',
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
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        return response()->json([
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveTaskRequest $request, $task)
    {
        $task = Task::find($task);

        if (! $task) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        if ($request->user()->cannot('update', $task)) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar esta tarea'
            ], 403);
        }

        $task->update($request->all());

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
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
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        if (auth()->user()->cannot('update', $task)) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar esta tarea'
            ], 403);
        }

        $task->delete();

        return response()->noContent();
    }
}
