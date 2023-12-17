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
    
    <div>
        <p>Followers: <span id="followersCount">{{ $user->followerUsers()->count() ?? 0 }}</span></p>
        <p>Following: <span id="followingCount">{{ $user->followingUsers()->count() ?? 0 }}</span></p>
    </div>

    @if(Auth::check() && Auth::user()->id !== $user->id)
        @php
            $isFollowing = Auth::user()->follows ? Auth::user()->follows->contains($user) : false;
        @endphp
        <div>
            <button id="followButton" class="btn btn-primary" onclick="toggleFollow({{ $user->id }})">
                @if($isFollowing)
                    Unfollow
                @else
                    Follow
                @endif
            </button>
        </div>
    @endif
</div>

@if(Auth::check() && Auth::user()->id !== $user->id)
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/follow.js') }}"></script>
@endif