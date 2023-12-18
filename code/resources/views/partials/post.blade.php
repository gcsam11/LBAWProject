@section('styles')
    <link href="{{ url('css/postcard.css') }}" rel="stylesheet">
@endsection

<div class="postcard" data-id="{{ $post->id }}">
<article class="post">
    <h2>{{ $post->title }}</h2>
    <div class="post-info">
        <strong>by </strong>
        @if(!str_contains($post->user->username, 'anonymous'))
            <strong><a href="{{ route('profile_page', ['id' => $post->user->id]) }}">{{ $post->user->name }}</a></strong>
        @else
            <strong class="anonymous_username">{{$post->user->username}}</strong>
        @endif
        <strong>on {{ date('Y-m-d', strtotime($post->postdate)) }} at {{ date('H:i:s', strtotime($post->postdate))}}</strong>
        <br>
        <p>{{ $post->topic->title ?? 'N/A' }}</p><br><br>
        <p><strong>{{ $post->caption }}</strong></p><br><br>
        <i class="fa-regular fa-circle-up"></i><br>
        <strong>{{ $post->upvotes - $post->downvotes }}</strong><br>
        <i class="fa-regular fa-circle-down"></i><br><br>
        <i class="fa-regular fa-comment"></i> <strong>{{ $post->comments->count() }}</strong><br><br>
    </div>
</article>
</div>