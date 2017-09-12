(function($) {
    /**
     * ThinkBlog 命名空间.
     * 所有全局函数和变量都要放在这个对象中。
     */
    var ThinkBlog = function() {
        // ---------------------------------------------------------------------
        // 延时触发事件
        this.delay = (function(){
            var timer = 0;
            return function(callback, ms = 1000){
                // console.debug(`delay to call function for ${ms} milliseconds`);

                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        // ---------------------------------------------------------------------
        // 客户端应用网址
        this.URL_HOME_PAGE          = 'URL_HOME_PAGE';
        this.URL_SIGNUP             = 'URL_SIGNUP';
        this.URL_LOGIN              = 'URL_LOGIN';
        this.URL_LOGOUT             = 'URL_LOGOUT';
        this.URL_RECAPTCHA          = 'URL_RECAPTCHA';
        this.URL_CHECK_RECAPTCHA    = 'URL_CHECK_RECAPTCHA';

        this.URL_SHOW_PROFILE       = 'URL_SHOW_PROFILE';
        this.URL_EDIT_PROFILE       = 'URL_EDIT_PROFILE';

        this.URL_POST_LIST          = 'URL_POST_LIST';
        this.URL_CREATE_POST        = 'URL_CREATE_POST';
        this.URL_UPDATE_POST        = 'URL_UPDATE_POST';
        this.URL_DELETE_POST        = 'URL_DELETE_POST';

        this.URL_API_SIGNUP         = 'URL_API_SIGNUP';
        this.URL_API_LOGIN          = 'URL_API_LOGIN';
        this.URL_API_SHOW_PROFILE   = 'URL_API_VIEW_PROFILE';
        this.URL_API_SAVE_PROFILE   = 'URL_API_EDIT_PROFILE';
        this.URL_API_POST_LIST      = 'URL_API_POST_LIST';
        this.URL_API_CREATE_POST    = 'URL_API_CREATE_POST';
        this.URL_API_SHOW_POST      = 'URL_API_SHOW_POST';
        this.URL_API_UPDATE_POST    = 'URL_API_UPDATE_POST';
        this.URL_API_DELETE_POST    = 'URL_API_DELETE_POST';

        var urls = {};

        this.getUrl = function(name) {
            return urls[name];
        };

        this.getUrls = function() {
            return urls;
        };

        this.setUrl = function(name, url) {
            urls[name] = url;
        };
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

    // 删除文章之前进行确认
    $('.post-listing [data-toggle=confirmation]').click(function (e) {
        console.debug('.post-listing [data-toggle=confirmation] clicked:');

        e.preventDefault();

        var url = $(this).data('delete-url'),
            $form = $('.post-listing .delete-post-form');
        console.debug('  url = ', url);
        $form.prop('action', url).submit();
    });

    // 显示验证码
    $('.signup #recaptcha_img').click(function (e) {
        // console.debug('.signup #recaptcha_img click:');

        $(".signup #recaptcha").val('');

        var url = "/recaptcha",
            time = new Date().getTime();
        $(this).prop({
            "src" : url + "/" + time
        });
    });

    // 检查验证码
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
