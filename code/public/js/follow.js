function updateFollowStatus(response) {
    // Handle success, e.g., update the button text and counters
    console.log(response);

    // Example: Update followers and following counters
    document.getElementById('followersCount').innerText = response.followersCount;
    document.getElementById('followingCount').innerText = response.followingCount;

    // Example: Update the button text
    const followButton = document.getElementById('followButton');
    followButton.innerText = response.isFollowing ? 'Unfollow' : 'Follow';
}

function toggleFollow(userId) {
    fetch(`/follow/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        credentials: 'same-origin',
    })
        .then(response => response.json())
        .then(updateFollowStatus)
        .catch(error => {
            // Handle error
            console.error(error);
        });
}
