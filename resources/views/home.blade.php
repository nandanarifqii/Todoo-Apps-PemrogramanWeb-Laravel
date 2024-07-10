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
        <div class="col-md-6 d-flex align-items-center">
            <div>
                <div class="mb-4">
                    <h6>Hi, {{ Auth::user()->name }}</h6>
                    <h1 class="fw-semibold">Start Managing Your Tasks</h1>
                </div>
                <div>
                    @if (Route::has('groups.index'))
                    @auth
                    @if (auth()->user()->group)
                    <a href="{{ route('groups.index') }}" class="btn btn-primary">
                        View Group
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-outline-primary">Add Personal Task</a>
                    @else
                    <h6 class="fst-italic">You haven't join any group!</h6>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        Create New Group
                    </a>
                    <a href="{{ route('groups.joinform') }}" class="btn btn-outline-primary">
                        Join Group
                    </a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 d-flex justify-content-center">
            <img src="{{ asset('images/home-illustration.svg') }}" alt="Illustration" class="img-fluid w-50">
        </div>
    </div>
</div>

@endsection