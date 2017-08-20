(function($) {
    /**
     * ThinkBlog 命名空间.
     * 所有全局函数和变量都要放在这个对象中。
     */
    var ThinkBlog = function() {
    };

    // 初始化全局 ThinkBlog 对象
    window.ThinkBlog = new ThinkBlog();
})(jQuery);

$(document).ready(function() {
    // 启用 Boostrap Confirmation
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        // other options
    });

    // 确认之后，删除文章
    $('.post-listing [data-toggle=confirmation]').click(function (e) {
        console.debug('.post-listing [data-toggle=confirmation] clicked:');

        e.preventDefault();

        var url = $(this).data('delete-url'),
            $form = $('.post-listing .delete-post-form');
        console.debug('  url = ', url);
        $form.prop('action', url).submit();
    });
});
