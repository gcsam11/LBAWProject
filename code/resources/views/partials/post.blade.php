<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2>{{ $post->title }}</h2>
        <a href="#" class="delete">&#10761;</a>
    </header>
    <ul>
        <!-- Display post details or any specific information -->
        <li>{{ $post->caption }}</li>
        <li>Posted by: {{ $post->user->name }}</li> <!-- Assuming you have a user relationship -->
        <!-- Display other relevant post details -->
    </ul>
    <form class="new_item">
        <input type="text" name="description" placeholder="new item">
    </form>
</article>