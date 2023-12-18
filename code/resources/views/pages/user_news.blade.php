@extends('layouts.app')

@section('title', 'My News')

@section('header', 'My News')

@section('content')
    <div class="search&sort">
        <input type="text" placeholder="Search for News">
        <button>Search</button>
        <br>
    </div>

    <div class="create_post">
        <button id="create_post">+</button>
    </div>

    @include('partials.filters')

    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    @include('partials.event')
    <br>

    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection