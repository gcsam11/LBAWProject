@section('styles')
    <link href="{{ url('css/postcard.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src={{ url('js/post.js') }} defer></script>
    <script type="text/javascript" src={{ url('js/readmore_post.js') }} defer></script>
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
        <p>{{ $post->topic->title ?? 'N/A' }}</p>
        <button class="followButton" onclick="toggleFollow({{ $post->topic->id }})" data-topic-id="{{ $post->topic->id }}">
        </button>
        <br><br>
        <p><strong>{{ $post->caption }}</strong></p><br><br>
        @php
            $userUpvoted = $post->checkIfUserUpvoted();
            $userDownvoted = $post->checkIfUserDownvoted();
            $id = $post->id;
            $upvoteId = $id . "upvoteButton";
            $downvoteId = $id . "downvoteButton";
        @endphp
        @auth
        <br>
        <button id="{{$upvoteId}}" class="{{ $userUpvoted ? 'clicked' : 'not-clicked' }}" onclick="event.preventDefault(); try { upvote({{ $post->id }}) } catch (e) { console.error(e); }">
            @if($userUpvoted)
                <i class="fa-solid fa-circle-up"></i>
            @else
                <i class="fa-regular fa-circle-up"></i>
            @endif
        </button>

        <input type="hidden" data-id="{{ $post->id }}" id="upvotes" value="{{ $post->upvotes }}">
        <input type="hidden" data-id="{{ $post->id }}" id="downvotes" value="{{ $post->downvotes }}">

        <div class="upvotes-downvotes" data-id="{{$post->id}}">
            <strong>{{ $post->upvotes - $post->downvotes }}</strong>
        </div>

        <button id="{{$downvoteId}}" class="{{ $userUpvoted ? 'clicked' : 'not-clicked' }}" onclick="event.preventDefault(); try { downvote({{ $post->id }}) } catch (e) { console.error(e); }">
            @if($userDownvoted)
                <i class="fa-solid fa-circle-down"></i>
            @else
                <i class="fa-regular fa-circle-down"></i>
            @endif
        </button>
        @endauth
        <br>
        <i class="fa-regular fa-comment"></i> <strong>{{ $post->comments->count() }}</strong><br><br>
    </div>
</article>
</div>
