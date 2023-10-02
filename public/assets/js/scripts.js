/*!
* Start Bootstrap - Modern Business v5.0.7 (https://startbootstrap.com/template-overviews/modern-business)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-modern-business/blob/master/LICENSE)
*/

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
 * Icons scroll-up, scroll-down
 */
const $scrollUpIcon = $("#scroll-up-icon");
const $scrollDownIcon = $("#scroll-down-icon");

// Show the scroll-down button initially
$scrollDownIcon.show();

$(window).on("scroll", function() {
    if ($(window).scrollTop() > 100) {
        $scrollUpIcon.show();
    } else {
        $scrollUpIcon.hide();
    }

    if ($(window).height() + $(window).scrollTop() >= $(document).height()) {
        $scrollDownIcon.hide();
    } else {
        $scrollDownIcon.show();
    }
});

$scrollUpIcon.on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, "fast");
});

$scrollDownIcon.on("click", function () {
    $("html, body").animate({ scrollTop: $(document).height() }, "fast");
});

