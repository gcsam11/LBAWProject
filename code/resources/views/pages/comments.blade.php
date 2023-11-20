<div class="comments">
    <br><hr><br>
    <h3>Comments</h3>
    <ul>
        @foreach ($comments as $comment)
            <li>
                <p>Title: {{ $comment->title }}</p>
                <p>Caption: {{ $comment->caption }}</p>
                <p>Comment Date: {{ $comment->commentdate }}</p>
                <p>Upvotes: {{ $comment->upvotes }}</p>
                <p>Downvotes: {{ $comment->downvotes }}</p>
                <p>Posted by: <a href="{{ route('profile_page', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a></p>
            </li>
        @endforeach
    </ul>
</div>