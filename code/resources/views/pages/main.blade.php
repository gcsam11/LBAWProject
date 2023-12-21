@extends('layouts.app')

@section('title', 'News Feed')

@section('header', 'News Feed')

@section('content')
    @include('partials.search')
    <div class="create_post">
        <button id="create_post">+</button>
    </div>

    @include('partials.filters')

    {{-- Posts--}}
    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
    <script src="{{ asset('js/create_post.js') }}"></script>
    
@endsection