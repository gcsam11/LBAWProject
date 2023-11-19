<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>Title:{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        <!-- Display post details or any specific information -->
        <li><strong>Caption:</strong> {{ $post->caption }}</li>
        <li><strong>postdate:</strong> {{ $post->postdate }}</li>
        <li><strong>upvotes:</strong> {{ $post->upvotes }}</li>
        <li><strong>downvotes:</strong> {{ $post->downvotes }}</li>
        <li><strong>user_id:</strong> {{ $post->user_id }}</li>
        <li><strong>topic_id:</strong> {{ $post->topic_id }}</li>

    </ul>
    <form class="new_item">
        <input type="text" name="description" placeholder="new item">
    </form>
</article>