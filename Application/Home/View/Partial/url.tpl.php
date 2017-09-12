<script type="text/javascript">
    // 应用网址注入客户端
    ThinkBlog.setUrl(ThinkBlog.URL_HOME_PAGE,        '{:U("/")}');
    ThinkBlog.setUrl(ThinkBlog.URL_SIGNUP,           '{:U("/signup")}');
    ThinkBlog.setUrl(ThinkBlog.URL_LOGIN,            '{:U("/login")}');
    ThinkBlog.setUrl(ThinkBlog.URL_LOGOUT,           '{:U("/logout")}');
    ThinkBlog.setUrl(ThinkBlog.URL_RECAPTCHA,        '{:U("/recaptcha")}');
    ThinkBlog.setUrl(ThinkBlog.URL_CHECK_RECAPTCHA,  '{:U("/check_recaptcha")}');

    ThinkBlog.setUrl(ThinkBlog.URL_SHOW_PROFILE,     '{:U("/profile")}');
    ThinkBlog.setUrl(ThinkBlog.URL_EDIT_PROFILE,     '{:U("/profile/edit")}');

    ThinkBlog.setUrl(ThinkBlog.URL_POST_LIST,        '{:U("/posts")}');
    ThinkBlog.setUrl(ThinkBlog.URL_CREATE_POST,      '{:U("/posts/create")}');
    ThinkBlog.setUrl(ThinkBlog.URL_UPDATE_POST,      '{:U("/posts/update/:id")}');
    ThinkBlog.setUrl(ThinkBlog.URL_DELETE_POST,      '{:U("/posts/delete/:id")}');

    ThinkBlog.setUrl(ThinkBlog.URL_API_SIGNUP,       '{:U("/api/signup")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_LOGIN,        '{:U("/api/login")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_SHOW_PROFILE, '{:U("/api/profile")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_SAVE_PROFILE, '{:U("/api/profile")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_POST_LIST,    '{:U("/api/posts")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_CREATE_POST,  '{:U("/api/posts")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_SHOW_POST,    '{:U("/api/posts/:id")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_UPDATE_POST,  '{:U("/api/posts/:id")}');
    ThinkBlog.setUrl(ThinkBlog.URL_API_DELETE_POST,  '{:U("/api/posts/:id")}');

    // console.debug('urls = ' , ThinkBlog.getUrls());
</script>
