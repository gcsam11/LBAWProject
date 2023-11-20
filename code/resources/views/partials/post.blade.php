<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        <!-- Display post details or any specific information -->
        <li><strong>Topic:</strong> {{ $post->topic->title }}</li>
        <li><strong>Caption:</strong> {{ $post->caption }}</li>
        <li><strong>Date:</strong> {{ $post->postdate }}</li>
        <li><strong>Upvotes:</strong> {{ $post->upvotes }}</li>
        <li><strong>Downvotes:</strong> {{ $post->downvotes }}</li>
        <li><strong>Posted by:</strong> {{ $post->user->name }}</li>
    </ul>
</article>