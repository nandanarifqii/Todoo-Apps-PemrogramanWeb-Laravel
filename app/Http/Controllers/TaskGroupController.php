<?php

namespace App\Http\Controllers;

use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskGroupController extends Controller
{

    //Newest
    public function index($sortBy)
    {
        $query = TaskGroup::query();

        if ($sortBy == 'priority') {
            $query->orderBy('priority', 'asc');
        } elseif ($sortBy == 'due_date') {
            $query->orderBy('due_date', 'asc');
        }

        $taskGroup = $query->get();

        return $taskGroup;
    }


    // New ONLY DELETE IF SORT BY FUNCTIONS WORKS
    // public function index()
    // {
    //     // Modify the logic to retrieve the task group data
    //     $sortBy = request()->input('sort_by', 'priority');

    //     if ($sortBy == 'priority') {
    //         $taskGroup = TaskGroup::orderBy('priority', 'asc')->get();
    //     } elseif ($sortBy == 'due_date') {
    //         $taskGroup = TaskGroup::orderBy('due_date', 'asc')->get();
    //     } else {
    //         $taskGroup = TaskGroup::all();
    //     }

    //     return $taskGroup;
    // }

    // Old
    // public function index(Request $request)
    // {
    //     // dd('Controller is called');
    //     //Nambahin dari sini
    //     $sortBy = $request->input('sort_by', 'priority');

    //     if ($sortBy == 'priority') {
    //         $taskGroup = TaskGroup::orderBy('priority', 'asc')->get();
    //     } elseif ($sortBy == 'due_date') {
    //         $taskGroup = TaskGroup::orderBy('due_date', 'asc')->get();
    //     } else {
    //         $taskGroup = TaskGroup::all();
    //     }
    //     //Sampai sini

    //     return view('groups.index', compact('taskGroup'));
    // }


    public function store(Request $request)
    {
        //New
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'required|in:Urgent,Normal,Low',
            'due_date' => 'required|date',
        ]);

        // Create a new task group
        $taskGroup = TaskGroup::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'due_date' => $request->input('due_date'),
            'group_id' => auth()->user()->group_id
        ]);

        //Old
        // $taskGroup = new TaskGroup;
        // $taskGroup->name = $request->input('name');
        // $taskGroup->description = $request->input('description');
        // $taskGroup->priority = $request->input('priority');
        // $taskGroup->due_date = $request->input('due_date');
        // $taskGroup->save();

        return redirect()->route('groups.index')->with('success', 'Task created successfully.');
    }

    public function editTask($group, $task)
    {
        $taskGroup = TaskGroup::findOrFail($task);

        // Check if the authenticated user is the creator of the task group
        if ($taskGroup->group->user_id !== auth()->id()) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to edit this task.');
        }

        return view('groups.editTask', compact('taskGroup'));
    }



    public function update(Request $request, $group, $task)
    {
        //New
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'required|in:Urgent,Normal,Low',
            'due_date' => 'required|date',
        ]);

        // Find the task by its ID
        $taskGroup = TaskGroup::where('group_id', $group)->findOrFail($task);

        // Update the task with the new data
        $taskGroup->name = $request->input('name');
        $taskGroup->description = $request->input('description');
        $taskGroup->priority = $request->input('priority');
        $taskGroup->due_date = $request->input('due_date');

        $taskGroup->save();


        //Old
        // $taskGroup->name = $request->input('name');
        // $taskGroup->description = $request->input('description');
        // $taskGroup->priority = $request->input('priority');
        // $taskGroup->due_date = $request->input('due_date');
        // $taskGroup->save();

        return redirect()->route('groups.index')->with('success', 'Task updated successfully.');
    }

    public function destroy($group, $task)
    {
        $taskGroup = TaskGroup::findOrFail($task);

        // Check if the authenticated user is the creator of the task group
        if ($taskGroup->group->user_id !== auth()->id()) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to delete this task.');
        }

        $taskGroup->delete();

        return redirect()->route('groups.index')->with('success', 'Task deleted successfully.');
    }


    public function markAsDone(Request $request, $group, $task)
    {
        // Find the task group by its ID
        $taskGroup = TaskGroup::where('group_id', $group)->findOrFail($task);

        // Update the hasFinished status
        $taskGroup->hasFinished = !$taskGroup->hasFinished;
        $taskGroup->save();

        $user = Auth::user();
        $group = $user->group;

        $sortBy = request()->input('sort_by', 'priority');

        $taskGroupController = new TaskGroupController();
        $taskGroup = $taskGroupController->index($sortBy);

        $finishedTaskGroups = $taskGroup->filter(function ($taskGroup) {
            return $taskGroup->hasFinished;
        });

        session()->flash('success', 'Task status updated.');
        return redirect()->route('groups.index', compact('group', 'taskGroup', 'finishedTaskGroups'));
    }
}
