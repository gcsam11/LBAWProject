@extends('layouts.app')

@section('title', 'Posts')

@section('content')

    @foreach($posts as $post)
        @include('partials.post', ['post' => $post])
                <button class="btn btn-primary" onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
                    Read more...
                </button>
    @endforeach

@endsection