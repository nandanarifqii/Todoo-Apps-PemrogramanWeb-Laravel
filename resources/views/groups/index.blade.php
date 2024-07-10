@extends('layouts.app')

@section('content')

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<div class="container">

    <div class="row">

        @isset($group)
        <h1>{{ $group->name }}</h1>
        <div class="col-md-6">
            <p>{{ $group->description }}</p>
            <form action="{{ route('groups.showMembers', ['group' => $group->id]) }}" method="GET">
                @csrf
                <button type="button" class="btn btn-outline-primary" onclick="toggleMembersList()">Show Group Members</button>
            </form>

            <div id="groupMembers" style="display: none;">
                <p>Group Members:</p>
                <ul class="list-group">
                    @foreach($group->users as $member)
                    <li class="list-group-item">{{ $member->name }}
                        @if ($group->user_id === auth()->user()->id && $member->id !== $group->user_id)
                        <form method="POST" action="{{ route('groups.kickMember', ['group' => $group->id, 'member' => $member->id]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Kick</button>
                        </form>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Code Joiin Group -->
        <div class="col-md-6">
            <div style="display: flex; align-items: center;">
                <!-- <p style="margin-right: 10px;">Code Group: <b>{{ $group->joincode }}</b></p> -->
                <p class="mt-3 d-none">Code Group : </p>
                <p class="mt-3 ms-2 d-none" id="groupCode">{{ $group->joincode }}</p>
                <button class="btn btn-outline-primary mt-6 mb-3" onclick="copyGroupCode()">Code Group : {{ $group->joincode }}</button>
            </div>

            <div id="editGroup">
                <form method="GET" action="{{ route('groups.edit', ['group' => $group->id]) }}" style="display: inline;">
                    @csrf
                    <button class="btn btn-outline-primary" type="submit">Edit Group</button>
                </form>
            </div>

        </div>
    </div>

    <!-- Old -->
    <!-- <div class="row" style="display:inline-block">
        @isset($group)
        <h1>{{ $group->name }}</h1>

        <form action="{{ route('groups.showMembers', ['group' => $group->id]) }}" method="GET">
            @csrf
            <button type="submit">Show Group Members</button>
        </form>


        @if(isset($showMembers) && $showMembers)
        <div>
            <h3>Group Members:</h3>
            <ul>
                @foreach($group->creator->group->users as $member)
                <li>{{ $member->name }}</li>
                @endforeach
            </ul>
        </div>
        @endif

    </div> -->

    @endisset



    <form action="{{ route('taskgroups.store', $group) }}" method="POST">
        @csrf
        <br>
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Input task name here" required>
        </div>
        <br>
        <div class="form-group">
            <label for="description">Task Description</label>
            <input type="text" class="form-control" name="description" id="description" placeholder="Write task detail here">
        </div>
        <br>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="Urgent">Urgent</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <br>
            <div class="form-group col-md-4">
                <label for="due_date">Due Date</label>
                <!-- New -->
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}">
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Add Task</button>
    </form>

    <!-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    <!-- Sort by for Tasks -->
    <!-- <div>
        <br>
        <form action="{{ route('groups.index') }}" method="GET">
            <label for="sort_by">Sort By:</label>
            <select name="sort_by" id="sort_by">
                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
                <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Due Date</option>
            </select>
            <button type="submit">Confirm</button>
        </form>
        <br>
    </div> -->
    <br>
    <form action="{{ route('groups.index') }}" method="GET">

        <div class="dropdown sort">
            <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <li><a class="dropdown-item" href="{{ route('groups.index', ['sort_by' => 'priority']) }}">Priority</a></li>
                    <li><a class="dropdown-item" href="{{ route('groups.index', ['sort_by' => 'due_date']) }}">Due Date</a></li>
                </ul>
            </div>
            <div class="sort-info mt-3 mb-3">
                @if(request('sort_by') == "priority")
                <span class="badge bg-primary">Priority</span>
                @elseif(request('sort_by') == "due_date")
                <span class="badge bg-primary">Due Date</span>
                @else
                <span class="badge bg-primary">Default</span>
                @endif
            </div>
        </div>

    </form>

    <br>



    <!-- menampilkan tasks group -->
    @if(isset($taskGroup) && count($taskGroup) > 0)
    <table class="table styled-table">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Due Date</th>
                <th>Actions</th>
                <th>Mark as Done</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskGroup as $task)
            @if(!$task->hasFinished || ($task->hasFinished && $task->due_date >= now()->format('Y-m-d')))
            <tr>
                <!-- <td>{{ $task->id }}</td> -->
                <td>{{ $task->name }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->priority }}</td>
                <td>{{ $task->created_at }}</td>
                <td>{{ $task->updated_at }}</td>
                <td>{{ $task->due_date }}</td>
                <td>
                    <a href="{{ route('taskgroups.editTask', ['group' => $group->id, 'task' => $task->id]) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form action="{{ route('taskgroups.destroy', ['group' => $group->id, 'task' => $task->id]) }}" method="POST" style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('taskgroups.markAsDone', ['group' => $group->id, 'task' => $task->id]) }}" method="POST">
                        @csrf
                        <input class="form-check-input" type="checkbox" name="done" onchange="this.form.submit()" {{ $task->hasFinished ? 'checked' : '' }}>
                    </form>
                </td>

            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @else
    <p>No tasks found.</p>
    @endif

    <br><br>


    @if(isset($finishedTaskGroups) && count($finishedTaskGroups) > 0)
    <h2>Finished Tasks</h2>
    <table class="table styled-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            @php
            $taskCount = 0;
            @endphp
            @foreach($finishedTaskGroups as $finishedTask)
            @php
            $taskCount++;
            @endphp
            <tr id="taskRow{{$taskCount}}" {!! $taskCount> 5 ? 'style="display: none;"' : '' !!}>
                <td>{{ $finishedTask->name }}</td>
                <td>{{ $finishedTask->description }}</td>
                <td>{{ $finishedTask->priority }}</td>
                <td>{{ $finishedTask->created_at }}</td>
                <td>{{ $finishedTask->updated_at }}</td>
                <td>{{ $finishedTask->due_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(count($finishedTaskGroups) > 5)
    <button id="showMoreButton" class="btn btn-primary">Show More</button>
    @endif
    @else
    <p>No finished tasks found.</p>
    @endif


    <!-- menampilkan tasks group -->


    <br>
    @isset($group)


    @if (Auth::user()->id === $group->user_id)
    <form method="POST" action="{{ route('groups.delete', $group) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
    </form>
    @else
    <form method="POST" action="{{ route('groups.leave') }}" style="display: inline;">
        @csrf
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to leave this group?')">Leave</button>
    </form>
    @endif

    @endisset
    <!--  @isset($group)
    tambahkan rute 
    

    <form method="POST" action="{{ route('groups.update', ['group' => $group->id]) }}" style="display: inline;">
        @csrf
        @method('PUT')
        <button class="btn btn-light" type="submit">Edit</button>
    </form>

    @endisset -->

</div>

<script>
    var showMoreButton = document.getElementById('showMoreButton');
    var taskRows = document.querySelectorAll('[id^="taskRow"]');
    var isShowingMore = false;

    for (var i = 6; i <= taskRows.length; i++) {
        taskRows[i - 1].style.display = 'none';
    }

    showMoreButton.addEventListener('click', function() {
        if (isShowingMore) {
            for (var i = 6; i <= taskRows.length; i++) {
                taskRows[i - 1].style.display = 'none';
            }
            showMoreButton.textContent = 'Show More';
            isShowingMore = false;
        } else {
            for (var i = 6; i <= taskRows.length; i++) {
                taskRows[i - 1].style.display = 'table-row';
            }
            showMoreButton.textContent = 'Show Less';
            isShowingMore = true;
        }
    });

    function toggleMembersList() {
        var membersList = document.getElementById('groupMembers');
        var button = document.querySelector('button');

        if (membersList.style.display === 'none') {
            membersList.style.display = 'block';
            button.textContent = 'Hide Group Members';
        } else {
            membersList.style.display = 'none';
            button.textContent = 'Show Group Members';
        }
    }

    function copyGroupCode() {
        const groupCode = document.getElementById('groupCode');
        const tempInput = document.createElement('input');
        tempInput.value = groupCode.textContent;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('Copy');
        document.body.removeChild(tempInput);
        alert('Kode Grup telah disalin!');
    }
</script>
@endsection