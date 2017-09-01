<?php
return [
    //--------------------------------------------------------------------------
    // 界面

    // 公用
    'EXCLAMATION_MARK'          => '！',
    'COLON'                     => '：',
    'VERION'                    => '版本',
    'NO_DATA_FOUND'             => '暂时没有数据',
    'SERIAL_NO'                 => '序号',
    'ACTION'                    => '操作',
    'CHANGE'                    => '修改',
    'DELETE'                    => '删除',
    'CONFIRM_TITLE'             => '请确认',
    'CONFIRM_TO_DELETE'         => '您确认要删除这篇{$model}吗？',
    'SUBMIT'                    => '提交',
    'SAVE'                      => '保存',
    'CANCEL'                    => '取消',

    // 应用程序
    'APPLICATION_NAME'          => 'ThinkPHP 博客',
    'APPLICATION_SHORT_DESC'    => '用 ThinkPHP 进行开发的演示程序',

    // 菜单
    'MENU_POSTS'        => '文章',
    'SWITCH_LANGUAGE'   => '语言',
    'CHINESE'           => '中文',
    'ENGLISH'           => '英文',

    // 页脚
    'WELCOME_TO_USE'    => '欢迎使用',

    // 主页
    'TECH_DESC'                 => '演示以下技术：',
    'REMOVE_ENTRY_IN_URL'       => '去掉地址里面的入口文件 {$entry_file}',
    'DEV_USING_DOCKER'          => '用 Docker 配置本地开发环境',
    'CONFIG_IN_ENV'             => '配置放入环境',
    'BASIC_CRUD'                => '基本的 CRUD',
    'BUILD_WITH_LARAVEL_MIX'    => '用 Laravel Mix 构建前端资源',
    'USER_AUTHENTICATION'       => '用户认证：用户是否是系统的合法用户，包括注册、登录、注销和忘记密码，等等',
    'USER_AUTHORIZATION'        => '用户授权：用户是否有权做某项操作，比如是否可以访问某个页面/某项功能、是否可以读/写/删某项数据，等等',
    'MULTI_LANGUAGES'           => '多语言支持',
    'MODEL_ASSOCIATION'         => '模型关联：模型之间的关联（一对一，一对多，属于，和多对多）',
    'WEB_API'                   => 'Web API',
    'ANGULARJS_1_CLIENT'        => 'AngularJS 1 客户端',
    'USING_HTTPS'               => '使用 HTTPS',
    'SOURCE_DESC'               => '演示程序的<a href="https://github.com/blackpuppy/thinkblog">源代码</a>可以自由获取。',

    // login
    'SIGN_UP'           => '注册新用户',
    'SIGNUP'            => '注册',
    'LOGIN'             => '登录',
    'LOGOUT'            => '注销',
    'USER_NAME'         => '用户名',
    'PASSWORD'          => '密码',
    'CONFIRM_PASSWORD'  => '确认密码',
    'EMAIL'             => '电子邮箱',
    'FULL_NAME'         => '姓名',
    'FIRST_NAME'        => '名',
    'LAST_NAME'         => '姓',
    'RECAPTCHA'         => '验证码',
    'REMEMBER_ME'       => '记住我',
    'FORGET_PASSWORD'   => '忘记密码',

    // 文章
    'POST'              => '文章',
    'POST_LISTING'      => '文章列表',
    'CREATE_POST'       => '添加文章',
    'CHANGE_POST'       => '修改文章',
    'TITLE'             => '标题',
    'CONTENT'           => '内容',

    //--------------------------------------------------------------------------
    // 数据验证

    // 验证规则
    // 'REQUIRED'                  => '{$field}必须填写！',

    // 数据验证 - 文章
    'TITLE_REQUIRED'            => '标题必须填写！',
    'CONTENT_REQUIRED'          => '内容必须填写！',

    // 数据验证 - 用户
    'NAME_REQUIRED'             => '用户名必须填写！',
    'NAME_DUPLICATE'            => '该用户名已被占用！请选择其他的用户名。',
    'PASSWORD_REQUIRED'         => '密码必须填写！',
    'PASSWORD_LENGTH'           => '密码至少5个字符，最多72个字符！',
    'CONFIRM_PASSWORD_DISMATCH' => '确认密码比匹配！',
    'FIRST_NAME_LENGTH'         => '名至少1个字符，最多255个字符！',
    'EMAIL_INVALID'             => '不合法的电子邮箱地址！',
    'EMAIL_DUPLICATE'           => '该电子邮箱地址已被占用！请选择其他的电子邮箱。',

    //--------------------------------------------------------------------------
    // 控制器

    // 文章
    'SAVE_POST_SUCCESS'     => '文章保存成功！',
    'SAVE_POST_FAILURE'     => '文章保存失败！',
    'POST_NOT_FOUND'        => '文章不存在！',
    'DELETE_POST_SUCCESS'   => '文章删除成功！',
    'DELETE_POST_FAILURE'   => '文章删除失败！',

    // 用户
    'SIGNUP_USER_SUCCESS'   => '用户注册成功！',
    'SIGNUP_USER_FAILURE'   => '用户注册失败！',
    'LOGIN_USER_SUCCESS'    => '用户登录成功！',
    'LOGIN_USER_FAILURE'    => '登录失败！用户名或者密码不正确。',
    'USER_NOT_FOUND'        => '用户不存在！',
    'DELETE_USER_SUCCESS'   => '用户删除成功！',
    'DELETE_USER_FAILURE'   => '用户删除失败！',

    //--------------------------------------------------------------------------
    // 用户认证与授权
    'UNAUTHORIZED'  => '未获授权访问！',
];
