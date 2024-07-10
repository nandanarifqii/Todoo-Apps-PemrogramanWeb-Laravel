@extends('layouts.app')

@section('content')
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="container">
    <h1>Edit Task</h1>
    <form action="{{ route('taskgroups.update', ['group' => $taskGroup->group_id, 'task' => $taskGroup->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mt-3">
            <label for="name">Task Name:</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $taskGroup->name }}" required>
        </div>
        <div class="form-group mt-3">
            <label for="description">Description Task</label>
            <textarea name="description" id="description" class="form-control">{{ $taskGroup->description }}</textarea>
        </div>
        <div class="form-group mt-3">
            <label for="priority">Priority Task</label>
            <select name="priority" id="priority" class="form-control" required>
                <option value="Urgent" {{ $taskGroup->priority === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="Normal" {{ $taskGroup->priority === 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="Low" {{ $taskGroup->priority === 'Low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>
        <div class="form-group mt-3">
            <label for="priority">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $taskGroup->due_date ?? '' }}">
        </div>
        <input type="hidden" name="group_id" value="{{ $taskGroup->group_id }}">
        <button type="submit" class="btn btn-primary mt-3">Update Task</button>
    </form>
</div>
@endsection