@extends('layouts.app')

@section('title', 'News Feed')

@section('header', 'News Feed')

@section('content')
    @include('partials.search')

    @include('partials.filters')

    <div class="create_post">
        <button id="create_post">+</button>
    </div>

    {{-- Posts--}}
    <div class="news">
        @include('partials.posts', ['posts' => $posts])
    </div>
@endsection