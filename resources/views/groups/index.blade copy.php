@extends('layouts.app')

@section('content')

<!-- @if (Auth::check()) -->
<!-- Display authenticated user-specific content here -->
<!-- @foreach ($groups as $group) -->
<!-- <h1>{{ $group->name }}</h1> -->
<!-- <p>{{ $group->description }}</p> -->
<!-- @endforeach -->
<!-- @else -->
<!-- Display content for non-authenticated users here -->
<!-- <p>Please log in to view the groups.</p> -->
<!-- @endif -->


// create task group dump
<!-- <form action="{{ route('groups.store') }}" method="POST">
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" placeholder="Input task name here" required>
        </div>
        <div class="form-group">
            <label for="description">Task Description</label>
            <input type="text" class="form-control" id="description" placeholder="Write task detail here">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control" placeholder="select priority" required>
                    <option value="Urgent">Urgent</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $taskGroup->due_date ?? '' }}">
            </div>
            <br>
            <form action="{{ route('taskGroup.store') }}" method="GET">
                <button type="submit" class="btn btn-primary">+ Add Task</button>
            </form>
        </div>
    </form> -->



<!-- <form method="POST" action="{{ route('groups.delete', $group) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        @if (Auth::user()->id === $group->user_id)
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
        @else
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to leave this group?')">Leave</button>
        @endif
    </form> -->

<!-- <div class="container">
    <h1>List Groups</h1>
    <ul>
        @foreach ($groups as $group)
            <li>
                <a class="btn btn-success"href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                <form method="POST" action="{{ route('groups.delete', $group) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
    </div> -->

<div class="container">
    <h1>{{ $group->name }}</h1>
    <p>{{ $group->description }}</p>

    <!-- ganti rute -->

    <form>
        <!-- <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Email</label>
      <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
    </div>
  </div> -->
        <div class="form-group">
            <label for="name">Task Name</label>
            <input type="text" class="form-control" id="name" placeholder="Input task name here" required>
        </div>
        <div class="form-group">
            <label for="description">Task Description</label>
            <input type="text" class="form-control" id="description" placeholder="Write task detail here">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="Urgent">Urgent</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="due_date">State</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date ?? '' }}">
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">+ Add Task</button>
    </form>

    <!-- <form action="">
        @csrf
        <div class="form-group">
            nama
        </div>
        <div class="form-group">
            deskripsi
        </div>
        <div class="form-row">
            <div class="col-md-5">
                priority
            </div>
            <div class="col-md-5">
                duedate
            </div>
            <div class="col-md-2">
                <button>submit</button>
            </div>
        </div>
    </form> -->

    <!-- <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="form-group mt-3">
                <label for="name">Task Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group mt-3">
                <label for="description">Description Task</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="col">
                <label for="priority">Priority Task</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="Urgent">Urgent</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="col">
                <label for="priority">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Task</button>
        </form> -->

    <br>
    <p>Kode Grup: <span id="groupCode">{{ $group->joincode }}</span></p>
    <button onclick="copyGroupCode()">Copy</button> <br> <br>
    <form method="POST" action="{{ route('groups.destroy', $group) }}" style="display: inline;">
        @csrf
        @method('DELETE')
        @if (Auth::user()->id === $group->user_id)
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
        @else
        <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you sure you want to leave this group?')">Leave</button>
        @endif
    </form>


    <!-- tambahkan rute -->
    <form method="POST" action="{{ route('groups.update', $group) }}" style="display: inline;">
        @csrf
        @method('PUT')
        <button class="btn btn-light" type="submit">Edit</button>
    </form>

</div>

<a href=""></a>

<script>
    function copyGroupCode() {
        const groupCode = document.getElementById('groupCode');
        const tempInput = document.createElement('input');
        tempInput.value = groupCode.textContent;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('Kode Grup telah disalin!');
    }
</script>
@endsection