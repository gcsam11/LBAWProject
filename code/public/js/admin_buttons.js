document.addEventListener('DOMContentLoaded', function () {
    var block_buttons = document.getElementsByClassName('admin_block');
    for (var i = 0; i < block_buttons.length; i++) {
        block_buttons[i].addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            window.location.href = '/profile/' + userId + '/block';
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var block_buttons = document.getElementsByClassName('admin_not_block');
    for (var i = 0; i < block_buttons.length; i++) {
        block_buttons[i].addEventListener('click', function() {
            var userId = this.getAttribute('data-id');
            window.location.href = '/profile/' + userId + '/unblock';
        });
    }
});