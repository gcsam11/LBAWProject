<div class="comments">
    <br><hr><br>
    <h3>Comments</h3>
        @foreach ($comments as $comment)
            <article class="comment">
                @can('update', $comment)
                    <form action="{{ route('comments.edit', ['id' => $comment->id]) }}" method="GET">
                        @csrf
                        <button type="submit">Edit</button>
                    </form>
                @endcan
                @can('delete', $comment)
                    <form action="{{ route('comments.delete', ['id' => $comment->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endcan
                <br><br>
                <strong>Title:</strong> {{ $comment->title }}<br><br>
                <strong>Caption:</strong> {{ $comment->caption }}<br><br>
                <strong>Comment Date:</strong> {{ $comment->commentdate }}<br><br>
                <strong>Upvotes:</strong> {{ $comment->upvotes }}<br><br>
                <strong>Downvotes:</strong> {{ $comment->downvotes }}<br><br>
                <strong>Posted by:</strong> <a href="{{ route('profile_page', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
            </article>
        @endforeach
    <br><hr><br>
</div>