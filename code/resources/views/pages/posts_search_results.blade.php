@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')

    @foreach($results as $post)
        @include('partials.posts', ['post' => $post])
        <button class="btn btn-primary" onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
            Read more...
        </button>
    @endforeach

@endsection