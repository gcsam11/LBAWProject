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
    
    <p>Followers: <span id="followersCount">{{ $user->followers ?? 0 }}</span></p>
    <p>Following: <span id="followingCount">{{ $user->following ?? 0 }}</span></p>

    @if(Auth::check() && Auth::user()->id !== $user->id)
        @php
            $isFollowing = Auth::user()->followingUsers->contains($user);
        @endphp
        <button id="followButton" onclick="toggleFollow({{ $user->id }})">
            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
        </button>
        <script src="{{ asset('js/follow.js') }}"></script>
    @endif
</div>