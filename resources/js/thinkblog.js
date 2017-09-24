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
        this.URL_ANGULARJS          = 'URL_ANGULARJS';
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

        this.URL_API_CONFIG_LIST    = 'URL_API_CONFIG_LIST';
        this.URL_API_SIGNUP         = 'URL_API_SIGNUP';
        this.URL_API_LOGIN          = 'URL_API_LOGIN';
        this.URL_API_SHOW_PROFILE   = 'URL_API_VIEW_PROFILE';
        this.URL_API_SAVE_PROFILE   = 'URL_API_EDIT_PROFILE';
        this.URL_API_POST_LIST      = 'URL_API_POST_LIST';
        this.URL_API_CREATE_POST    = 'URL_API_CREATE_POST';
        this.URL_API_SHOW_POST      = 'URL_API_SHOW_POST';
        this.URL_API_UPDATE_POST    = 'URL_API_UPDATE_POST';
        this.URL_API_DELETE_POST    = 'URL_API_DELETE_POST';
        this.URL_API_TRANSLATE      = 'URL_API_TRANSLATE';

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

        this.Base64 = {

            keyStr: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',

            encode: function (input) {
                var output = "";
                var chr1, chr2, chr3 = "";
                var enc1, enc2, enc3, enc4 = "";
                var i = 0;

                do {
                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);

                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;

                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }

                    output = output +
                        this.keyStr.charAt(enc1) +
                        this.keyStr.charAt(enc2) +
                        this.keyStr.charAt(enc3) +
                        this.keyStr.charAt(enc4);
                    chr1 = chr2 = chr3 = "";
                    enc1 = enc2 = enc3 = enc4 = "";
                } while (i < input.length);

                return output;
            },

            decode: function (input) {
                var output = "";
                var chr1, chr2, chr3 = "";
                var enc1, enc2, enc3, enc4 = "";
                var i = 0;

                // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
                var base64test = /[^A-Za-z0-9\+\/\=]/g;
                if (base64test.exec(input)) {
                    window.alert("There were invalid base64 characters in the input text.\n" +
                        "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                        "Expect errors in decoding.");
                }
                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                do {
                    enc1 = this.keyStr.indexOf(input.charAt(i++));
                    enc2 = this.keyStr.indexOf(input.charAt(i++));
                    enc3 = this.keyStr.indexOf(input.charAt(i++));
                    enc4 = this.keyStr.indexOf(input.charAt(i++));

                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;

                    output = output + String.fromCharCode(chr1);

                    if (enc3 != 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                        output = output + String.fromCharCode(chr3);
                    }

                    chr1 = chr2 = chr3 = "";
                    enc1 = enc2 = enc3 = enc4 = "";

                } while (i < input.length);

                return output;
            }
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
