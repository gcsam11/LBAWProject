@extends('layouts.app')

@section('title')
    {{ "News Feed" }}
@endsection

@section('header')
    {{ "News Feed" }}
@endsection

@section('content')
        <div class="search&sort">
            <input type="text" placeholder="Search for News">
            <button>Search</button>
            <!--
                <select name="sort_news" id="sort_news">
                    <option value="recent" selected="selected">Most Recent</option>
                    <option value="popular">Most Popular</option>
                </select>
                <button>Sort</button>
            -->
            <br>
        </div>
@endsection