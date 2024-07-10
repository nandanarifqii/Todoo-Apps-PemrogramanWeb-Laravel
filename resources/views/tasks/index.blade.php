@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Task Manager</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add Task</a>

    <div class="dropdown sort">
        <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            Sort By
        </button>
        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
            <li><a class="dropdown-item"
                    href="{{ route('tasks.index', ['sort_by' => 'priority']) }}">Priority</a></li>
            <li><a class="dropdown-item"
                    href="{{ route('tasks.index', ['sort_by' => 'due_date']) }}">Due Date</a></li>
        </ul>

        <div class="sort-info mt-3">
            @if($sortBy == "priority")
            <span class="badge bg-primary">Priority</span>
            @else
            <span class="badge bg-primary">Due Date</span>
            @endif
        </div>

    </div>




    <h2 class="task-h2">Task on Progress</h2>
    @if(count($tasksInProgress) > 0)
    <table class="table styled-table">
        <thead>
            <tr>
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
            @foreach($tasksInProgress as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->priority }}</td>
                <td>{{ $task->created_at }}</td>
                <td>{{ $task->updated_at }}</td>
                <td @if($task->due_date < now()->subDay()) style="color: red;" @endif>{{ $task->due_date}}
                    @if($task->due_date < now()->subDay()) <br><span style="color: red;">Overdue</span> @endif
                </td>
                <td>
                    <a href="{{ route('tasks.edit', $task->id) }}"
                        class="btn btn-outline-primary btn-sm">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                        style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('tasks.complete', ['task' => $task->id]) }}" method="POST">
                        @csrf
                        <input type="checkbox" name="completed" onchange="this.form.submit()"
                            {{ $task->completed ? 'checked' : '' }}>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No tasks in progress.</p>
    @endif

    <h2 class="task-h2">Task Completed</h2>
    @if(count($completedTasks) > 0)
    <table class="table styled-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Due Date</th>
                <th>Completed</th>
            </tr>
        </thead>
        <tbody>
            @foreach($completedTasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->priority }}</td>
                <td>{{ $task->created_at }}</td>
                <td>{{ $task->updated_at }}</td>
                <td>{{ $task->due_date }}</td>
                <td>
                    <form action="{{ route('tasks.inProgress', ['task' => $task->id]) }}" method="POST">
                        @csrf
                        <input type="checkbox" name="completed" onchange="this.form.submit()"
                            {{ $task->completed ? 'checked' : '' }}>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No completed tasks.</p>
    @endif

</div>
@endsection
