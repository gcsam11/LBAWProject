<div class="comments">
    <h3>Comments</h3>
    <ul>
        @foreach ($comments as $comment)
            <li>
                <p><strong>Title:</strong> {{ $comment->title }}</p>
                <p><strong>Caption:</strong> {{ $comment->caption }}</p>
                <p><strong>Comment Date:</strong> {{ $comment->commentdate }}</p>
                <p><strong>Upvotes:</strong> {{ $comment->upvotes }}</p>
                <p><strong>Downvotes:</strong> {{ $comment->downvotes }}</p>
                <p><strong>Posted by:</strong> {{ $comment->user->name }}</p>
            </li>
        @endforeach
    </ul>
</div>