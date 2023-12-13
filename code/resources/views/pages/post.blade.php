@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <section id="posts">
        @include('partials.post', ['post' => $post])
        @include('pages.comments', ['comments' => $comments])
    </section>
    {{-- Update Button --}}
    @can('update', $post)
    <div>
        <h2>Edit Post</h2>
        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="{{ $post->title }}">
            </div>
            <div>
                <label for="caption">Caption:</label>
                <textarea id="caption" name="caption">{{ $post->caption }}</textarea>
            </div>
            <button type="submit">Update Post</button>
        </form>
    </div>
    @endcan

    {{-- Delete Button --}}
    @can('delete', $post)
    <form action="{{ route('posts.delete', ['id' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Post</button>
    </form> 
    @endcan

    {{-- Create Comment Form --}}
    @auth
        <div>
            <h4>Add a Comment</h4>
            <form action="{{ route('comments.create', ['id' => $post->id]) }}" method="POST">
                @csrf
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <label for="caption">Caption:</label>
                <textarea id="caption" name="caption" required></textarea>
                <button type="submit">Post</button>
            </form>
        </div>
    @endauth

    {{-- Display Success Message --}}
    @if(session('success'))
        <script>
            // Store the success message in a JavaScript variable
            const successMessage = "{{ session('success') }}";
            // Output the success message to the console
            console.log(successMessage);
        </script>
    @endif
@endsection