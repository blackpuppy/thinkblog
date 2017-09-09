<?php
namespace Home\Model;

use Home\Model\BaseModel;

class UserModel extends BaseModel
{
    protected $_validate = [
        ['name', 'require', '{%NAME_REQUIRED}', self::MUST_VALIDATE],
        ['name', '', '{%NAME_DUPLICATE}', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT],

        ['password', 'require', '{%PASSWORD_REQUIRED}', self::MUST_VALIDATE,   '',       self::MODEL_INSERT],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::MUST_VALIDATE,   'length', self::MODEL_INSERT],
        ['password', 'require', '{%PASSWORD_REQUIRED}', self::VALUE_VALIDATE,  '',       self::MODEL_UPDATE],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::VALUE_VALIDATE,  'length', self::MODEL_UPDATE],

        ['confirm_password', 'password', '{%CONFIRM_PASSWORD_DISMATCH}', self::EXISTS_VALIDATE, 'confirm'],

        ['email',
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            '{%EMAIL_INVALID}', self::EXISTS_VALIDATE, 'regex'],
        ['email', '', '{%EMAIL_DUPLICATE}', self::EXISTS_VALIDATE, 'unique'],

        // 用户登录时的验证规则
        ['name',     'require', '{%NAME_REQUIRED}',     self::MUST_VALIDATE, '',       self::MODEL_LOGIN],
        ['password', 'require', '{%PASSWORD_REQUIRED}', self::MUST_VALIDATE, '',       self::MODEL_LOGIN],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::MUST_VALIDATE, 'length', self::MODEL_LOGIN],
    ];

    protected $_filter = [
        'password' => ['encryptPassword'],
    ];

    protected $_link = [
        'Profile' => [
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Profile',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'profile',
        ],
        'Post' => [
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Post',
            'foreign_key'   => 'author_user_id',
            'mapping_name'  => 'posts',
            'mapping_order' => 'create_at desc',
        ],
    ];

    /**
     * 包含敏感信息的属性/字段。
     */
    protected $_sensitive = [
        'password',
        'confirm_password',
    ];

    public function login($username, $password)
    {
        $msg = PHP_EOL . 'Home\Model\UserModel::login():'
            . PHP_EOL . '  $username = ' . $username
            . PHP_EOL . '  $password = ' . $password;

        $authenticated = false;

        $user = $this->relation(true)->where(['name' => $username])->find();

        $msg .= PHP_EOL . '  $user = ' . print_r($user, true);

        if ($user &&
            password_verify($password, $user['password'])
        ) {
            $authenticated = $user;
        }

        $msg .= PHP_EOL . '  $authenticated = ' . print_r($authenticated, true);
        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $authenticated;
    }
}
