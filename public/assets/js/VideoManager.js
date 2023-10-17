$(function() {
    /**
     * Add form field for more video urls
     */
    var $videoUrlsContainer = $('#video-urls-container');
    var $addVideoUrlButton = $('#add-url');
    var prototype = $videoUrlsContainer.data('prototype');

    $addVideoUrlButton.on('click', function () {
        var index = $videoUrlsContainer.children().length; // Get the current index
        var newForm = prototype.replace(/__name__/g, index); // Replace '__name__' with the index

        // Create a div to wrap the form field and remove button
        var $formGroup = $('<div class="form-group mb-2"></div>');
        $formGroup.append(newForm);

        // Add the remove button
        var $removeButton = $('<button type="button" class="btn btn-danger remove-url ms-2">Supprimer URL</button>');
        $formGroup.append($removeButton);

        // Append the entire form group to the container
        $videoUrlsContainer.append($formGroup);
    });

    $videoUrlsContainer.on('click', '.remove-url', function () {
        $(this).closest('.form-group').remove();
    });

    /**
     * Modify a video
     */
    $(".modify-video-button").on("click", function() {
        // Get the imageID from the data attribute
        var videoID = $(this).data('video-id');
        var row = $(this).closest('.row');
        var index = $(this).closest('.media-container').data('index');

        // Send an AJAX request to delete the image
        $.ajax({
            url: '/delete-video/' + videoID,
            method: 'DELETE',
            success: function(response) {
                // Remove the image container from the front end
                $(this).closest('.media-container').remove();
                console.log(response);

                // Create a new div to wrap input video url
                var mediaContainer = $('<div class="col-md-3 mb-4 media-container"></div>');

                // Create a new input element
                var input = $('<input>', {
                    type: 'url',
                    name: 'add_trick_form[videos][' + index + ']',
                    id: 'add_trick_form_videos_' + index,
                });

                mediaContainer.append(input);

                // Append the input element to the container
                row.append(mediaContainer);

            }.bind(this),
            error: function(error) {
                // Handle errors and display error messages
                console.error(error);
            }
        });
    });

    /**
     * Delete a video
     */
    $(".delete-video-button").on("click", function() {
        // Get the videoID from the data attribute
        var videoID = $(this).data('video-id');

        // Send an AJAX request to delete the video
        $.ajax({
            url: '/delete-video/' + videoID,
            method: 'DELETE',
            success: function(response) {
                // Remove the video container from the front end
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
