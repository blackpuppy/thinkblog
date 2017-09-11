(function($) {
    /**
     * ThinkBlog 命名空间.
     * 所有全局函数和变量都要放在这个对象中。
     */
    var ThinkBlog = function() {
        this.delay = (function(){
            var timer = 0;
            return function(callback, ms = 1000){
                // console.debug(`delay to call function for ${ms} milliseconds`);

                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();
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

    $('.signup #recaptcha_img').click(function (e) {
        // console.debug('.signup #recaptcha_img click:');

        $(".signup #recaptcha").val('');

        var url = "/recaptcha",
            time = new Date().getTime();
        $(this).prop({
            "src" : url + "/" + time
        });
    });

    $(".signup #recaptcha").keyup(function() {
        ThinkBlog.delay(function () {
            var recaptcha = $(".signup #recaptcha").val();

            // console.debug('.signup #recaptcha keyup: recaptcha = ', recaptcha);

            $.post("/check_recaptcha",
                { code : recaptcha },
                function(valid) {
                    // console.debug('#recaptcha keyup: valid = ', valid);

                    $(".signup #signup_button").prop('disabled', valid !== true);
                }
            );
        }, 500);
    });
});
