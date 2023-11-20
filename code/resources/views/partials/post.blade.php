<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>Title:{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        <!-- Display post details or any specific information -->
        <li><strong>topic:</strong> {{ $post->topic->title }}</li>
        <li><strong>Caption:</strong> {{ $post->caption }}</li>
        <li><strong>postdate:</strong> {{ $post->postdate }}</li>
        <li><strong>upvotes:</strong> {{ $post->upvotes }}</li>
        <li><strong>downvotes:</strong> {{ $post->downvotes }}</li>
    </ul>
</article>