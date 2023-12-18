@extends('layouts.forms')
<head>
    <title>Create Post</title>
</head>
@section('header')
    {{ "Publish new Post" }}
@endsection

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<form enctype="multipart/form-data" method="POST" action="{{ route('posts.create') }}">
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

  <label for="caption">Caption</label>
  <textarea id="caption" type="text" name="caption" required></textarea>

  <label id="box_container" for="image_input2">
    <div class="text2">
      <i class="fa-solid fa-upload"></i>
    </div>
    <input type="file" name="images[]" accept="image/*" id="image_input2" multiple>
  </label>
  <br>
  <label for="topic_id">Select Topic</label>
  <select id="topic_id" name="topic_id">
      <option value="none" selected disabled>Please select a topic</option>
      @foreach($topics as $topic)
          <option value="{{ $topic->id }}">{{ $topic->title }}</option>
      @endforeach
  </select>

  <button type="submit">Publish</button>
</form>
<script src="createPostPage.js"></script>

@endsection

