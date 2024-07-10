<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Group;
use App\Models\TaskGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {

        $userId = auth()->id();

        // Retrieve count of unfinished tasks
        $taskCount = Task::where('user_id', $userId)
            ->where('completed', false)
            ->count();

        // Retrieve count of unfinished task groups
        $taskGroupCount = TaskGroup::whereHas('group', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('hasFinished', false)
            ->count();

        // Retrieve count of groups
        $groupCount = Group::where('user_id', $userId)->count();

        return view('profile.index', compact('taskCount', 'taskGroupCount', 'groupCount'));

        // Old
        // return view('profile.index');
    }
}
