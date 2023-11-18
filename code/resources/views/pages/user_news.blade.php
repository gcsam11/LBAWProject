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
            <!-- Fetch news on the database -->

            <!-- if user clicks on upvote or downvote redirect to login page -->
        </div>
        <br>
        <div class="create_post">
            <button id="create_post">+</button>
        </div>
        <script src="{{ asset('js/create_post.js') }}"></script>
@endsection