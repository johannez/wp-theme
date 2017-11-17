// Definitions

(function () {

    if (typeof window.SITE === "undefined") {
        window.SITE = {};
    }

})();

// Invocations

$(function () {

    // Enable mobile menu.
    var $menu = $("#nav-mobile").mmenu({
        "offCanvas": {
            "position": "right"
        },
        "extensions": [
            "theme-dark"
        ],
        slidingSubmenus: true
    });
    var $icon = $("#my-icon");
    var API = $menu.data("mmenu");

    $icon.on("click", function () {
        API.open();
    });

    API.bind("open:finish", function () {
        setTimeout(function () {
            $icon.addClass("is-active");
        }, 100);
    });
    API.bind("close:finish", function () {
        setTimeout(function () {
            $icon.removeClass("is-active");
        }, 100);
    });
});

