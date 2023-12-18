function updateFollowStatus(response) {
    console.log(response);

    //Update followers and following counters
    document.getElementById('followersCount').innerText = response.followersCount;
    document.getElementById('followingCount').innerText = response.followingCount;

    //Update the button text
    const followButton = document.getElementById('followButton');
    followButton.innerText = response.isFollowing ? 'Unfollow' : 'Follow';
}

function toggleFollow(userId) {
    fetch(`/follow/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
        .then(response => response.json())
        .then(updateFollowStatus)
        .catch(error => {
            console.error(error);
        });
}
