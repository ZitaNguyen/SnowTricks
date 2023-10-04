$(function() {
    $(".delete-image-button").on("click", function() {
        // Get the imageID from the data attribute
        var imageId = $(this).data('image-id');

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete_image/' + imageId, // Replace with your Symfony route
            method: 'DELETE',
            success: function(response) {
                // Remove the image container from the front end
                $(this).closest('.media-container').remove();
                console.log(response);
            }.bind(this), // Use bind to retain the button context
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });
});
