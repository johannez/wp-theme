// Definitions

(function() {

    if (typeof window.SITE === "undefined") {
        window.SITE = {};
    }

    SITE.mainMenuDropDown = function() {
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
    SITE.mainMenuDropDown();

});
//# sourceMappingURL=site.js.map