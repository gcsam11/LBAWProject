@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <!-- Add content for profile page -->

    <form method="GET" action="{{ route('logout') }}">
        <button type="submit">Logout</button>
    </form>
@endsection