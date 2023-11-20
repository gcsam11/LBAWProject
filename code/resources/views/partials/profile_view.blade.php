<div>
    <h2>Name: {{ $user['name'] }}</h2>
    <img src="{{ $user['image'] }}" alt="Profile Image"><br>

    <p>Username: {{ $user['username'] }}</p>
    <p>Email: {{ $user['email'] }}</p>
    <p>Gender: {{ $user['gender'] }}</p>
    <p>Birthday: {{ $user['birthday'] }}</p>
    <p>Reputation: {{ $user['reputation'] }}</p>
</div>