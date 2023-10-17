$(function() {
    /**
     * Modify an image
     */
    $(".modify-image-button").on("click", function() {
        // Get the imageID from the data attribute
        var imageID = $(this).data('image-id');

        var row = $(this).closest('.row');

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete-image/' + imageID,
            method: 'DELETE',
            success: function(response) {
                // Remove the image container from the front end
                $(this).closest('.media-container').remove();
                console.log(response);

                // Create a new div to wrap input video url
                var mediaContainer = $('<div class="col-md-3 mb-4 media-container"></div>');

                // Create a new input element
                var input = $('<input>', {
                    type: 'file',
                    name: 'add_trick_form[images][]',
                    accept: 'image/*',
                    multiple: 'multiple'
                });

                mediaContainer.append(input);

                // Append the input element to the container
                row.append(mediaContainer);

            }.bind(this), // Use bind to retain the button context
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });

    /**
     * Delete an image
     */
    $(".delete-image-button").on("click", function() {
        // Get the imageID from the data attribute
        var imageID = $(this).data('image-id');

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete-image/' + imageID,
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
