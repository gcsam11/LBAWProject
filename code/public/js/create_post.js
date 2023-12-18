document.addEventListener('DOMContentLoaded', function () {
    var createPostButton = document.getElementById('create_post');
    var topicSelect = document.getElementById('topic_id');

    if (createPostButton) {
        createPostButton.addEventListener('click', function () {

                // Redirect the user to the create post page
                window.location.href = createPostUrl;
        });
    }
});
