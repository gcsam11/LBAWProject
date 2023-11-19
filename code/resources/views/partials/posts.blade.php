<!-- partial.posts.blade.php -->
@foreach($posts as $post)
    <div class="post">
        <h3>{{ $post->title }}</h3>
        <p>{{ $post->caption }}</p>
        <!-- Display other post details as needed -->
    </div>

    <script>
        console.log("Post ID: {{ $post->id }}");
        console.log("Title: {{ $post->title }}");
        console.log("Caption: {{ $post->caption }}");
        console.log("----------------------------");
    </script>
@endforeach