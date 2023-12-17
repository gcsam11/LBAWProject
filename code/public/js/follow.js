function updateFollowStatus(response) {
    // Handle success, e.g., update the button text and counters
    console.log(response.data);

    // Example: Update followers and following counters
    document.getElementById('followersCount').innerText = response.data.followersCount;
    document.getElementById('followingCount').innerText = response.data.followingCount;

    // Example: Update the button text
    const followButton = document.getElementById('followButton');
    followButton.innerText = response.data.isFollowing ? 'Unfollow' : 'Follow';
}

function toggleFollow(userId) {
    axios.post(`/follow/${userId}`)
        .then(updateFollowStatus)
        .catch(error => {
            // Handle error
            console.error(error);
        });
}