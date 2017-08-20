(function($) {
    /**
     * ThinkBlog namespace.
     * All global function and variable must belong to this object.
     */
    var ThinkBlog = function() {
    };

    //Initialize global ThinkBlog object
    window.ThinkBlog = new ThinkBlog();
})(jQuery);

$(document).ready(function() {
    // Enable Boostrap Confirmation
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        // other options
    });
});
