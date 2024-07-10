@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add Task</h1>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="form-group mt-3">
                <label for="name">Task Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group mt-3">
                <label for="description">Description Task</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group mt-3">
                <label for="priority">Priority Task</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="Urgent">Urgent</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="priority">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Task</button>
        </form>
    </div>
@endsection
