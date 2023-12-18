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
</div>