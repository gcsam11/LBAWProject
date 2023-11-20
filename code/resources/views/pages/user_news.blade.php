{{-- @extends('layouts.app')

<!-- USER HAS TO BE LOGGED IN -->

@section('title')
    {{ "My News" }}
@endsection

@section('header')
    {{ "My News" }}
@endsection

@section('content')
        <div class="search&sort">
            <input type="text" placeholder="Search for News">
            <button>Search</button>
            <!--
                <select name="sort_news" id="sort_news">
                    <option value="recent" selected="selected">Most Recent</option>
                    <option value="popular">Most Popular</option>
                </select>
                <button>Sort</button>
            -->
            <br>
        </div>
        <div class="news">
            <!-- Fetch news on the database that belong only to the user -->
        </div>
        <br>
        <div class="create_post">
            <button id="create_post">+</button>
        </div>
        <script>
            var createPostUrl = @json(route('create_post'));
        </script>
        <script src="{{ asset('js/create_post.js') }}"></script>
@endsection --}}

@extends('layouts.app')

<!-- USER HAS TO BE LOGGED IN -->

@section('title')
    {{ "My News" }}
@endsection

@section('header')
    {{ "My News" }}
@endsection

@section('content')
    <div class="search&sort">
        <input type="text" placeholder="Search for News">
        <button>Search</button>
        <br>
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
    <br>
    <div class="create_post">
        <button id="create_post">+</button>
    </div>
    <script>
        var createPostUrl = @json(route('create_post'));
    </script>
    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection