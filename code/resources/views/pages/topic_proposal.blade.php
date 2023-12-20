@extends('layouts.app')

@section('title')
    {{ "Profile" }}
@endsection

@section('header')
    {{ "Profile" }}
@endsection

@section('content')
    <div>
        <h2>Create Topic Proposal</h2>
        <form method="POST" action="{{ route('createTopicProposal') }}">
            @csrf
            <div>
                <label for="topic_name">Topic Proposal Name<span>*</span></label>
                <input type="text" id="topic_name" name="topic_name" placeholder="Topic Name" required>
            </div>
            <div>
                <label for="caption">Caption</label>
                <textarea id="caption" name="caption" placeholder="Message" required></textarea>
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection