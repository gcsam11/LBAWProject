function toggleFollow(userId) {
    var dataRequest = new FormData();
    dataRequest.append('followed_id', userId);

    fetch(`/follow/${userId}`, {
        method: 'POST',
        body: dataRequest,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function(data) {
        document.getElementById('followersCount').innerText = data.followersCount;
        document.getElementById('followingsCount').innerText = data.followingsCount;

        var followButton = document.getElementById('followButton');
        followButton.textContent = data.isFollowing ? 'Unfollow' : 'Follow';       
    })
    .catch(function(error) {
        console.error('Fetch Error:', error);
    });
}