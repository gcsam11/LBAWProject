@extends('layouts.forms')

@section('title', 'Filtered Posts')
@section('header', 'Filtered Posts')

@section('content')
    @if ($posts->isEmpty())
        <p>No Posts were found with these filters.</p>
    @else
        @foreach($posts as $post)
        @include('partials.post', ['post' => $post])
                <button class="btn btn-primary" onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
                    Read more...
                </button>
        @endforeach
    @endif
@endsection
