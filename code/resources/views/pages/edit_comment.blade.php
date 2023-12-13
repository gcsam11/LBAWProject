@extends('layouts.app')

@section('title', 'Edit Comment')

@section('content')
    <h2>Edit Comment</h2>
    <form action="{{ route('comments.update', ['id' => $comment->id]) }}" method="POST">
        @csrf
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="{{ $comment->title }}" required>
        </div>
        <div>
            <label for="caption">Caption:</label>
            <textarea id="caption" name="caption" required>{{ $comment->caption }}</textarea>
        </div>
        <button type="submit">Update</button>
    </form>
@endsection
