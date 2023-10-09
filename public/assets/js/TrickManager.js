$(function() {
    $(".delete-trick-button").on("click", function() {
        // Get trick slug from the data attribute
        var slug = $(this).data('slug');

        // Send an AJAX request to delete the trick
        $.ajax({
            url: '/delete-trick/' + slug,
            method: 'DELETE',
            success: function(response) {
                // Redirect to the home page
                window.location.href = response.redirect;
            },
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });
});
