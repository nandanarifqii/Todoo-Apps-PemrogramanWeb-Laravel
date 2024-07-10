<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }


    public function create()
    {
        $user = Auth::user();

        // Check if the user already has a group
        if ($user->group) {
            return redirect()->back()->with('error', 'You can only create one group.');
        }
        return view('groups.create');
    }

    public function joinForm()
    {
        return view('groups.joinForm');
    }

    public function edit(Group $group)
    {
        $user = auth()->user();

        // Check if the authenticated user is the group creator
        if ($group->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to edit this group.');
        }

        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        // Check if the authenticated user is the group creator
        if (auth()->user()->id !== $group->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to update this group.');
        }

        $group->update([
            'description' => $request->input('description'),
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Group description updated successfully.');
    }


    public function join(Request $request)
    {
        $joincode = $request->input('joincode');
        $group = Group::where('joincode', $joincode)->first();

        if (!$group) {
            return redirect()->back()->with('error', 'Invalid join code.');
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



    // public function store(Request $request)
    // {
    //     $group = Group::create([
    //         'name' => $request->input('name'),
    //         'description' => $request->input('description'),
    //     ]);

    //     $usernames = $request->input('usernames');

    //     if (!empty($usernames) && is_array($usernames)) {
    //         foreach ($usernames as $username) {
    //             $user = User::where('username', $username)->firstOrFail();
    //             $group->users()->attach($user->id);
    //         }
    //     }

    //     return redirect()->route('groups.index');
    // }

    public function show(Group $group)
    {
        $groups = Group::all();
        $members = $group->users()->get();
        return view('groups.show', compact('group', 'groups', 'members'));
    }


    // public function delete(Group $group)
    // {
    //     // Detach all users from the group before deleting
    //     // $group->users()->detach();

    //     // Delete the group
    //     $group->delete();

    //     return redirect()->route('groups.index');
    // }

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
        $user->group()->dissociate();
        $user->save();

        return redirect()->route('groups.index')->with('success', 'Left group successfully.');
    }


    public function destroy(Group $group)
    {
        $user = Auth::user();

        // Check if the authenticated user is the group creator
        if ($group->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this group.');
        }

        // Detach all users from the group before deleting
        $group->users()->detach();

        // Delete the group
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }



    // Arya Comment karena udah automically lewat groupModel

    // public function generateUniqueCode()
    // {
    // $code = Str::random(8); // Menghasilkan kode unik dengan panjang 8 karakter
    // // memaastikan kode tersebut belum digunakan sebelumnya
    //     while (Grup::where('group_code', $code)->exists()) {
    //         $code = Str::random(8);
    //     }
    // return $code;
    // }



}
