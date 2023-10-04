$(function() {
    $(".delete-video-button").on("click", function() {
        // Get the videoID from the data attribute
        var videoID = $(this).data('video-id');

        // Send an AJAX request to delete the video
        $.ajax({
            url: '/delete_video/' + videoID,
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
