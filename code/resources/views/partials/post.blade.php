<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
        <!-- Display post details or any specific information -->
        <strong>Topic:</strong> {{ $post->topic->title }}<br><br>
        <strong>Caption:</strong> {{ $post->caption }}<br><br>
        <strong>Date:</strong> {{ $post->postdate }}<br><br>
        <strong>Upvotes:</strong> {{ $post->upvotes }}<br><br>
        <strong>Downvotes:</strong> {{ $post->downvotes }}<br><br>
        <strong>Posted by:</strong> <a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a>
        <button class="not-clicked" onclick="upvote({{ $post->id }})">Upvote!</button>

</article>