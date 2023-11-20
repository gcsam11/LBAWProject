@extends('layouts.app')

@section('title', 'News Feed')

@section('header', 'News Feed')

@section('content')

    <div class="search&sort">
        <input type="text" placeholder="Search for News">
        <button>Search</button>
        <br>
    </div>
    <div class="create_post">
        <button id="create_post">+</button>
    </div>
    <div class="navigation">
        <!-- Buttons to switch between routes -->
        <a href="{{ route('posts.recent') }}">Recent Posts</a>
        <a href="{{ route('posts.top') }}">Top Posts</a>
    </div>
    <div class="news">
        <!-- Fetch news from the database -->
        @foreach($posts as $post)
            @include('partials.post', ['post' => $post])
            <button class="btn btn-primary" onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
                Read more...
            </button>
        @endforeach
    </div>
    <script>
        var createPostUrl = @json(route('create_post'));
    </script>
    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection