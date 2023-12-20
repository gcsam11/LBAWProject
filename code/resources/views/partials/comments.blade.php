<div class="comments">
        @foreach ($comments as $comment)
            <div class = "commentcard">
            <article class="comment">
                <h3>{{ $comment->title }}</h3><strong>by</strong> <a href="{{ route('profile_page', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
                <strong>on {{ date('Y-m-d', strtotime($comment->postdate)) }} at {{ date('H:i:s', strtotime($comment->postdate))}}</strong><br><br>
                <p>{{ $comment->caption }}</p>
                <button class="not-clicked">
                    <i class="fa-regular fa-circle-up"></i>
                </button><br>

                <input type="hidden" data-id="{{ $post->id }}" id="upvotes" value="{{ $post->upvotes }}">
                <input type="hidden" data-id="{{ $post->id }}" id="downvotes" value="{{ $post->downvotes }}">

                <strong>{{ $comment->upvotes - $comment->downvotes }}</strong><br>

                <button class="not-clicked">
                    <i class="fa-regular fa-circle-down"></i>
                </button>
                @if($comment->image)
                    <img src="{{ asset('post/'.$comment->image->filename) }}" alt="Comment Image">
                @endif
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
            </article>
            </div>
        @endforeach
    <br><hr><br>
</div>