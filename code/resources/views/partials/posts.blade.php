@foreach($posts as $post)
    <article class="post" data-id="{{ $post->id }}">
        <header>
            <h2>{{ $post->title }}</h2>
        </header>
        <!-- Display post details or any specific information -->
        <strong>Topic:</strong> {{ $post->topic->title ?? 'N/A' }}<br><br>
        <strong>Caption:</strong> {{ $post->caption }}<br><br>
        <strong>Date:</strong> {{ $post->postdate }}<br><br>
        <strong>Upvotes:</strong> {{ $post->upvotes }}<br><br>
        <strong>Downvotes:</strong> {{ $post->downvotes }}<br><br>
        <strong>Posted by:</strong> <a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a>
        <div>
            <a href="{{ route('posts.show', ['id' => $post->id]) }}" class="see-more">
                <button type="button">See More</button>
            </a>
        </div>
@endforeach