@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Task</h1>
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mt-3">
                <label for="name">Task Name:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $task->name }}" required>
            </div>
            <div class="form-group mt-3">
                <label for="description">Description Task</label>
                <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
            </div>
            <div class="form-group mt-3">
                <label for="priority">Priority Task</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="Urgent" {{ $task->priority === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="Normal" {{ $task->priority === 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Low" {{ $task->priority === 'Low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="priority">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Task</button>
        </form>
    </div>
@endsection
