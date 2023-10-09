$(function() {
    $(".delete-trick-button").on("click", function() {
        // Get trick slug from the data attribute
        var slug = $(this).data('slug');

        // Send an AJAX request to delete the trick
        $.ajax({
            url: '/delete-trick/' + slug,
            method: 'DELETE',
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });
});
