@section('styles')
    <link href="{{ url('css/postcard.css') }}" rel="stylesheet">
@endsection

<div class="postcard" data-id="{{ $post->id }}">
<article class="post">
    <h2>{{ $post->title }}</h2>
    <div class="post-info">
        <strong>by </strong>
        @if(!str_contains($post->user->username, 'anonymous'))
            <strong><a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a></strong>
        @else
            <strong class="anonymous_username">{{$post->user->username}}</strong>
        @endif
        <strong>on {{ date('Y-m-d', strtotime($post->postdate)) }} at {{ date('H:i:s', strtotime($post->postdate))}}</strong>
        <br>
        <p>{{ $post->topic->title ?? 'N/A' }}</p><br><br>
        <p><strong>{{ $post->caption }}</strong></p><br><br>
        <div class="upvotes" data-id="{{ $post->id }}"><i class="fa-regular fa-circle-up"></i></div><br>
        <strong>{{ $post->upvotes - $post->downvotes }}</strong><br>
        <div class="downvotes" data-id="{{ $post->id }}"><i class="fa-regular fa-circle-down"></i></div><br><br>
        <i class="fa-regular fa-comment"></i> <strong>{{ $post->comments->count() }}</strong><br><br>
        @php
            $userUpvoted = $post->checkIfUserUpvoted();
            $userDownvoted = $post->checkIfUserDownvoted();
            $id = $post->id;
            $upvoteId = $id . "upvoteButton";
            $downvoteId = $id . "downvoteButton";
        @endphp
        @auth
        <br>
        <button id="{{$upvoteId}}"class="{{ $userUpvoted ? 'clicked' : 'not-clicked' }}" onclick="upvote({{ $post->id }})">
            @if($userUpvoted)
                Upvoted
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
                </svg>
            @endif
        </button>
        <button id="{{$downvoteId}}"class="{{ $userDownvoted ? 'clicked' : 'not-clicked' }}" onclick="downvote({{ $post->id }})">
            @if($userDownvoted)
                Downvote
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
                </svg>
            @endif
        </button>
        @endauth
    </div>
</article>
</div>