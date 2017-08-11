// Definitions

(function() {

    if (typeof window.MY_THEME === "undefined") {
        window.MY_THEME = {};
    }

    MY_THEME.mainMenuDropDown = function() {
        $('#nav-main > ul > li.menu-item-has-children').hover(function() {
            if (!$(this).hasClass('open')) {
                $(this).addClass('open');
            }
        }, function() {
            $(this).removeClass('open');
        });
    }

})();


// Invocations

$(function () {

    // Make SVG maps work in IE.
    svg4everybody();

    // Handle main menu dropdowns.
    mainMenuDropDown();

});