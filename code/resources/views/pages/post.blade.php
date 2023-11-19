@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <section id="posts">
        @include('partials.post', ['post' => $post])
        @include('pages.comments', ['comments' => $comments])
    </section>

    {{-- Delete Button --}}
    @can('delete', $post)
    <form action="{{ route('posts.delete', $post->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Post</button>
    </form> 
    @endcan
    @if(session('success'))
        <script>
            // Store the success message in a JavaScript variable
            const successMessage = "{{ session('success') }}";
            // Output the success message to the console
            console.log(successMessage);
        </script>
    @endif
@endsection