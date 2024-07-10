@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center text-center vh-100">
        <div>
            <h1>Welcome to Todoo App</h1>
            <p>Easily create, track, and prioritize your tasks</p>
            <p class="mt-4">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/home') }}" class="btn btn-primary">Home</a>
                @else
                <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                @endif
                @endauth
                @endif
            </p>
        </div>
    </div>
</div>

@endsection