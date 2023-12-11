<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
        <!-- Display post details or any specific information -->
        <p><strong>Topic:</strong> {{ $post->topic->title }}</p>
        <p><strong>Caption:</strong> {{ $post->caption }}</p>
        <p><strong>Date:</strong> {{ $post->postdate }}</p>
        <p class="upvotes" data-id="{{ $post->id }}"><strong>Upvotes:</strong> {{ $post->upvotes }}</p>
        <p class="downvotes" data-id="{{ $post->id }}"><strong>Downvotes:</strong> {{ $post->downvotes }}</p>
        <strong>Posted by:</strong> <a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a>
        @php
            $userUpvoted = $post->checkIfUserUpvoted();
        @endphp
        <button class="{{ $userUpvoted ? 'clicked' : 'not-clicked' }}" onclick="upvote({{ $post->id }})">
            @if($userUpvoted)
                Upvoted
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
                </svg>
            @endif
        </button>

</article>