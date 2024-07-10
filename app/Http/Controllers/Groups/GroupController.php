<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Http\Controllers\TaskGroupController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class GroupController extends Controller
{
    //Newest untuk sort by
    public function index(TaskGroupController $taskGroupController)
    {
        $user = Auth::user();

        if (!$user->group) {
            return redirect()->route('home')->with('error', 'You do not have a group.');
        }

        $group = $user->group;

        $sortBy = request()->input('sort_by', 'priority');

        $taskGroup = $taskGroupController->index($sortBy);

        $finishedTaskGroups = $taskGroup->filter(function ($taskGroup) {
            return $taskGroup->hasFinished;
        });

        return view('groups.index', compact('group', 'taskGroup', 'finishedTaskGroups'));
        // return view('groups.index', compact('group', 'taskGroup'));
    }
    // //New ONLY DELETE IF THE SORT BY FUNCTION WORKS
    // public function index(TaskGroupController $taskGroupController)
    // {
    //     $user = Auth::user();

    //     if (!$user->group) {
    //         return redirect()->route('home')->with('error', 'You do not have a group.');
    //     }

    //     $group = $user->group;
    //     $taskGroup = $taskGroupController->index();

    //     return view('groups.index', compact('group', 'taskGroup'));
    // }


    // Old
    // public function index()
    // {
    //     // Yang ini ngembaliin semua grup
    //     // $groups = Group::all();
    //     // return view('groups.index', compact('groups'));

    //     $user = Auth::user();

    //     if (!$user->group) {
    //         return redirect()->route('home')->with('error', 'You do not have a group.');
    //     }

    //     $group = $user->group;

    //     return view('groups.index', compact('group'));
    // }


    public function create()
    {
        $user = Auth::user();

        // Check if the user already has a group
        if ($user->group) {
            return redirect()->back()->with('error', 'You can only create one group.');
        }
        return view('groups.create');
    }


    public function edit(Group $group)
    {
        $user = auth()->user();

        // Check if the authenticated user is the group creator
        if ($group->user_id !== $user->id) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to edit this group since you are not the creator.');
        }

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        // Check if the authenticated user is the group creator
        if (auth()->user()->id !== $group->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to update this group.');
        }

        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Update the group with the validated data
        $group->update($validatedData);

        // Flash the success message
        Session::flash('success', 'Group information updated successfully.');

        return redirect()->route('groups.index');
    }




    public function joinForm()
    {
        return view('groups.joinform');
    }


    public function join(Request $request)
    {

        $joincode = $request->input('joincode');


        $group = Group::where('joincode', $joincode)->first();

        if (!$group) {
            return redirect()->back()->withErrors(['error' => 'Invalid join code.']);
        }

        $user = Auth::user();

        // Check if the user is already a member of a group
        if ($user->group_id) {
            return redirect()->back()->with('error', 'You are already a member of a group.');
        }

        // Associate the user with the group
        $user->group_id = $group->id;
        /** @var \App\Models\User $user **/
        $user->save();

        return redirect()->route('groups.index')->with('success', 'Joined group successfully.');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if the user already has a group
        if ($user->group) {
            return redirect()->back()->with('error', 'You can only create one group.');
        }
        //Add generate uniqejoincode
        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'user_id' => $user->id,
        ]);


        // Associate the group with the creator (user)
        $user->group_id = $group->id;
        /** @var \App\Models\User $user **/
        $user->save();

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }



    public function show(Group $group)
    {
        //New
        $members = $group->users;
        // Old
        // $members = $group->users()->get();

        return view('groups.show', compact('group', 'members'));
    }

    //Leave group seperti biasa. regular member only
    public function leave()
    {
        $user = Auth::user();

        // Check if the user is a member of a group
        if (!$user->group) {
            return redirect()->back()->with('error', 'You are not a member of any group.');
        }

        // Disassociate the user from the group
        $user->group_id = null;
        /** @var \App\Models\User $user **/
        $user->save();

        return redirect()->route('home')->with('success', 'Left group successfully.');
    }

    public function kickMember(Group $group, User $member)
    {
        $user = Auth::user();

        // Check if the authenticated user is the group creator
        if ($group->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to kick members from this group.');
        }

        // Check if the member is actually part of the group
        if (!$group->users->contains($member)) {
            return redirect()->back()->with('error', 'The selected member does not belong to this group.');
        }

        // Check if the member is the group creator
        if ($member->id === $group->user_id) {
            return redirect()->back()->with('error', 'You cannot kick the group creator.');
        }

        // Disassociate the member from the group
        $member->group_id = null;
        $member->save();

        return redirect()->back()->with('success', 'Member kicked successfully.');
    }



    //Kick member kemudian menghapus group. Creator only
    public function delete(Group $group)
    {
        $user = Auth::user();

        // Check if the authenticated user is the group creator
        if ($group->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this group.');
        }

        // Remove the association between the group and its users
        $group->users()->update(['group_id' => null]);

        // Delete the group
        $group->delete();

        return redirect()->route('home')->with('success', 'Group deleted successfully.');
    }

    //Menampilkan list member
    public function showMembers()
    {
        $user = Auth::user();
        $group = $user->group;

        if (!$group) {
            return redirect()->route('groups.index')->with('error', 'You are not a member of any group.');
        }

        $members = $group->users;
        $showMembers = true;

        return view('groups.index', compact('group', 'members', 'showMembers'));
    }
}
