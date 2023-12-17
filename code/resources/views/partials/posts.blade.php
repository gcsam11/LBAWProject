@foreach($posts as $post)
    @include('partials.post', ['post' => $post])
    <button onclick="window.location='{{ route('posts.show', ['id' => $post->id]) }}'">
        Read more...
    </button>
@endforeach
