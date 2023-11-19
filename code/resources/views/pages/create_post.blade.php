@extends('layouts.forms')
<head>
    <title>Create Post</title>
</head>
@section('header')
    {{ "Publish new Post" }}
@endsection

@section('content')
<form method="POST" action="{{ route('posts.create') }}">
  {{ csrf_field() }}

  <!--
  <label for="topic">Topic</label>
  <input id="topic" type="text" name="topic" value=1 required autofocus>
  @if ($errors->has('topic'))
    <span class="error">
      {{ $errors->first('topic') }}
    </span>
  @endif
  -->

  <label for="title">Title</label>
  <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus>
  @if ($errors->has('title'))
    <span class="error">
      {{ $errors->first('title') }}
    </span>
  @endif

  <label for="caption">Caption</label>
  <textarea id="caption" type="text" name="caption" required></textarea>
  @if ($errors->has('caption'))
    <span class="error">
      {{ $errors->first('caption') }}
    </span>
  @endif

  <button type="submit">
    Publish
  </button>
</form>
@endsection