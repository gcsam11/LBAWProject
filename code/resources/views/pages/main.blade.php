@extends('layouts.app')

@section('title', 'News Feed')

@section('scripts')
<script type="text/javascript" src={{ url('js/readmore_post.js') }} defer></script>
@endsection

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