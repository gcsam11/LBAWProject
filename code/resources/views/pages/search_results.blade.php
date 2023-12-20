@extends('layouts.app')

@section('title', 'Search Results')

@section('header', 'Search Results')

@section('content')
    @if($posts !== null)
        @include('partials.posts', ['posts' => $posts])
    @endif
@endsection