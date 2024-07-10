@extends('layouts.app')

@section('content')

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif



<div class="container">
    <h1>Join Group by Entering Code</h1>
    <form action="{{ route('groups.join') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="joinCode">Join Code</label>
            <input type="text" name="joincode" id="joincode" class="form-control" placeholder="paste code group here">
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Join Group</button>
    </form>
</div>

@endsection