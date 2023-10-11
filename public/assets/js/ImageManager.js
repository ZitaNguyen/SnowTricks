$(function() {
    /**
     * Modify an image
     */
    $('[id^="imageFile-"]').hide();

    $(".modify-image-button").on("click", function() {
        // Get the imageID from the data attribute
        var imageID = $(this).data('image-id');
        var imageUpload = $("#imageFile-" + imageID);

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete-image/' + imageID, // Replace with your Symfony route
            method: 'DELETE',
            success: function(response) {
                // Remove the image container from the front end
                $(this).closest('.media-container').remove();
                console.log(response);

                // Show file input
                imageUpload.show();

            }.bind(this), // Use bind to retain the button context
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });

    $(".delete-image-button").on("click", function() {
        // Get the imageID from the data attribute
        var imageID = $(this).data('image-id');

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete-image/' + imageID, // Replace with your Symfony route
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
