@extends('layouts.app')

@section('title', 'News Feed')

@section('header', 'News Feed')

@section('content')

    <div class="search&sort">
        <form action="{{ route('posts.search') }}" method="GET">
            <input id="search_term" name="search_term" type="text" value="{{ old('search_term') }}" placeholder="Search for News">
            @if ($errors->has('search_term'))
                <span class="error">
                    {{ $errors->first('search_term') }}
                </span>
            @endif    
            <button type="submit">Search</button>
        </form>
        <br>
    </div>
    <div class="create_post">
        <button id="create_post">+</button>
    </div>
    <div class="navigation">
        <form id="sortForm">
            <label for="sortSelector">Sort Posts By:</label>
            <select id="sortSelector" name="sort">
                <option value="{{ route('posts.top') }}" {{ Request::is('main/top*') ? 'selected' : '' }}>Top Posts</option>
                <option value="{{ route('posts.recent') }}" {{ Request::is('main/recent*') ? 'selected' : '' }}>Recent Posts</option>
            </select>

            <button type="submit">Sort</button>
        </form>

        <script src="{{ asset('js/sort_posts.js') }}"></script>
    </div>
    <div class="news">
        <!-- Fetch news from the database -->
        @foreach($posts as $post)
            @include('partials.post', ['post' => $post])
            <button class="btn btn-primary" onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
                Read more...
            </button>
        @endforeach
        @include('partials.notification')
    </div>
    <script>
        var createPostUrl = @json(route('create_post'));
    </script>
    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection