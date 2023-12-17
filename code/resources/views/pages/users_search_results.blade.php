@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')

    @foreach($results as $user)
        @include('partials.user_row', ['user' => $user])
        <button class="btn btn-primary" onclick="window.location='{{ route('profile_page', ['id' => $user->id]) }}'">
            Profile
        </button>
        @if(Auth::check() && Auth::user()->id !== $user->id)
            @if(Auth::user()->follows->contains($user))
                <button class="btn btn-danger" onclick="toggleFollow({{ $user->id }})">Unfollow</button>
            @else
                <button class="btn btn-primary" onclick="toggleFollow({{ $user->id }})">Follow</button>
            @endif
        @endif
    @endforeach

    @if(Auth::check() && Auth::user()->id !== $user->id)
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="{{ asset('js/follow.js') }}"></script>
    @endif
@endsection