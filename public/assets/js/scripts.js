/*!
* Start Bootstrap - Modern Business v5.0.7 (https://startbootstrap.com/template-overviews/modern-business)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-modern-business/blob/master/LICENSE)
*/

$(function() {
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
});
