@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')

    @foreach($results as $user)
        @include('partials.user_row', ['user' => $user])
        <button class="btn btn-primary" onclick="window.location='{{ route('profile_page', ['id' => $user->id]) }}'">
            Profile
        </button>
    @endforeach

@endsection