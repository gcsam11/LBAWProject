@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')

    @foreach($results as $post)
        @include('partials.posts', ['post' => $post])
    @endforeach

@endsection