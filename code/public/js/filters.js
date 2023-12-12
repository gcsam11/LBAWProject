$(document).ready(function() {
    $('.filters form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Get form data
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

        $.ajax({
            url: filterPostsApplyRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
            },
            success: function(response) {
                if (response.success) {
                    $('.news').html(response.html); // Update .news with the received HTML
                } else {
                    // Handle errors if necessary
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status); // Log the error
            }
        });
    });
});