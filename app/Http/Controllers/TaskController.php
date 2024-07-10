<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    
    public function index(Request $request)
{
    $user_id = auth()->user()->id;
    $sortBy = $request->input('sort_by', 'priority');

    if ($sortBy == 'priority') {
        $tasksInProgress = Task::where('completed', false)
            ->where('user_id', $user_id)
            ->orderBy('priority', 'asc')->get();
        $completedTasks = Task::where('completed', true)
            ->where('user_id', $user_id)
            ->orderBy('priority', 'asc')->get();
    } elseif ($sortBy == 'due_date') {
        $tasksInProgress = Task::where('completed', false)
            ->where('user_id', $user_id)
            ->orderBy('due_date', 'asc')->get();
        $completedTasks = Task::where('completed', true)
            ->where('user_id', $user_id)
            ->orderBy('due_date', 'asc')->get();
    } else {
        $tasksInProgress = Task::where('completed', false)
            ->where('user_id', $user_id)
            ->get();
        $completedTasks = Task::where('completed', true)
            ->where('user_id', $user_id)
            ->get();
    }

    return view('tasks.index', compact('tasksInProgress', 'completedTasks', 'sortBy'));
}

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $task = Task::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'due_date' => $request->input('due_date'),
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->priority = $request->input('priority');
        $task->due_date = $request->input('due_date');
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function complete(Request $request, Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return redirect()->route('tasks.index');
    }

    public function inProgressTask(Request $request, Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return redirect()->route('tasks.index');
    }
}
