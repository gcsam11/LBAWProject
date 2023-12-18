<div>
    <h2>Name: {{ $user['name'] }}</h2>
    
    <div class=circle_container>
        <img src="{{ $user->getProfileImage() }}" alt="Profile Image"><br>
    </div>
    <p>Username: {{ $user['username'] }}</p>
    <p>Email: {{ $user['email'] }}</p>
    <p>Gender: {{ $user['gender'] }}</p>
    <p>Birthday: {{ $user['birthday'] }}</p>
    <p>Reputation: {{ $user['reputation'] }}</p>
    
    <p>Followers: <span id="followersCount">{{ $user->followers()->count() ?? 0 }}</span></p>
    <p>Following: <span id="followingCount">{{ $user->following()->count() ?? 0 }}</span></p>

    @if(Auth::check() && Auth::user()->id !== $user->id)
        @php
            $following = Auth::user()->following;
            $isFollowing = $following instanceof \Illuminate\Database\Eloquent\Collection && $following->contains($user);
        @endphp
        <button id="followButton" onclick="toggleFollow({{ $user->id }})">
            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
        </button>
    @endif
</div>

@if(Auth::check() && Auth::user()->id !== $user->id)
    <script src="{{ asset('js/follow.js') }}"></script>
@endif