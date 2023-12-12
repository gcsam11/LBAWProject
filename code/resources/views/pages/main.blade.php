@extends('layouts.app')

@section('title', 'News Feed')

@section('header', 'News Feed')

@section('content')
    {{-- load for ajax --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- for the date picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

    {{-- filters --}}
    <div class="filters">
        <form action="{{ route('filter.posts.apply') }}" method="GET" id="filterForm">

            <label for="filter_sort">Sort By:</label>
            <select id="filter_sort" name="sort">
                <option value="dateDown">Date - Newest to Oldest</option>
                <option value="dateUp">Date - Oldest to Newest</option>
                <option value="voteDown">Votes - Most to Least</option>
                <option value="voteUp">Votes - Least to Most</option>
            </select>


            <!-- Minimum date filter -->
            <label for="minimum_date">Minimum Date:</label>
            <input type="text" id="minimum_date" name="minimum_date">

            <!-- Maximum date filter -->
            <label for="maximum_date">Maximum Date:</label>
            <input type="text" id="maximum_date" name="maximum_date">

            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script src="{{ asset('js/datePickerConfig.js') }}"></script>
            <script>
                initializeDatePickers();
            </script>

            <!-- Upvote filter -->
            <label for="minimum_upvote">Minimum Upvote:</label>
            <input type="number" id="minimum_upvote" name="minimum_upvote" min="0">
            <label for="maximum_upvote">Maximum Upvote:</label>
            <input type="number" id="maximum_upvote" name="maximum_upvote" min="0">
    
            <!-- Downvote filter -->
            <label for="minimum_downvote">Minimum Downvote:</label>
            <input type="number" id="minimum_downvote" name="minimum_downvote" min="0">
            <label for="maximum_downvote">Maximum Downvote:</label>
            <input type="number" id="maximum_downvote" name="maximum_downvote" min="0">
    
            <!-- User filter -->
            <label for="user_name">User Name:</label>
            <input type="text" id="user_name" name="user_name">
    
            <button type="submit">Apply Filters</button>
            <button type="button" onclick="clearFilters()">Clear Filters</button>
        </form>
    </div>

    {{-- Posts--}}
    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    <script>
        var createPostUrl = @json(route('create_post'));
        var filterPostsApplyRoute  = @json(route('filter.posts.apply'));
    </script>
    <script src="{{ asset('js/create_post.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
@endsection