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

    @include('partials.filters')

    {{-- Posts--}}
    <script src="{{ asset('js/app.js') }}"></script>
    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    <script src="{{ asset('js/create_post.js') }}"></script>
@endsection