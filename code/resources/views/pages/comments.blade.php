<div class="comments">
    <h3>Comments</h3>
    <ul>
        @foreach ($comments as $comment)
            <li>
                <p>Title: {{ $comment->title }}</p>
                <p>Caption: {{ $comment->caption }}</p>
                <p>Comment Date: {{ $comment->commentdate }}</p>
                <p>Upvotes: {{ $comment->upvotes }}</p>
                <p>Downvotes: {{ $comment->downvotes }}</p>
                <p>Posted by: {{ $comment->user->name }}</p>
            </li>
        @endforeach
    </ul>
</div>