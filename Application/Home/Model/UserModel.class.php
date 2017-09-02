<?php
namespace Home\Model;

use Home\Model\BaseModel;

class UserModel extends BaseModel
{
    const USER_LOGIN = 4;   // 用户登录

    protected $_validate = [
        ['name', 'require', '{%NAME_REQUIRED}'],
        ['name', '', '{%NAME_DUPLICATE}', self::EXISTS_VALIDATE, 'unique', self::MODEL_BOTH],
        ['password', 'require', '{%PASSWORD_REQUIRED}'],
        ['password', '5,72', '{%PASSWORD_LENGTH}', self::EXISTS_VALIDATE, 'length'],
        ['confirm_password', 'password', '{%CONFIRM_PASSWORD_DISMATCH}', self::EXISTS_VALIDATE, 'confirm'],
        ['first_name', '1,255', '{%FIRST_NAME_LENGTH}', self::EXISTS_VALIDATE, 'length'],
        ['email',
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            '{%EMAIL_INVALID}', self::EXISTS_VALIDATE, 'regex'],
        ['email', '', '{%EMAIL_DUPLICATE}', self::EXISTS_VALIDATE, 'unique'],

        // 用户登录时的验证规则
        ['name',     'require', '{%NAME_REQUIRED}',     self::MUST_VALIDATE, '', self::USER_LOGIN],
        ['password', 'require', '{%PASSWORD_REQUIRED}', self::MUST_VALIDATE, '', self::USER_LOGIN],
    ];

    protected $_filter = [
        'password' => ['encryptPassword'],
    ];

    protected $_link = [
        'Post' => [
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Post',
            'foreign_key'   => 'author_user_id',
            'mapping_name'  => 'posts',
            'mapping_order' => 'create_at desc',
        ],
    ];

    public function fullName()
    {
        // return substr(LANG_SET, 0, 2) === 'zh' ?
        //     $fullName = $this->last_name . ' ' . $this->first_name :
        //     $fullName = $this->first_name . ' ' . $this->last_name;

        return UserModel::getFullName($this);
    }

    public static function getFullName(mixed $user, string $lastName = null)
    {
        $msg = 'UserModel::getFullName():'
            . PHP_EOL . '  $user = ' . print_r($user, true)
            . PHP_EOL . '  $lastName = ' . $lastName;

        $fullName = "";

        if (is_string($user)) {
            $firstName = $user;
        } elseif (is_array($user)) {
            $firstName = $user['first_name'];
            $lastName = $user['last_name'];
        } elseif (is_subclass_of($user, 'UserModel')) {
            $firstName = $user->first_name;
            $lastName = $user->last_name;
        }

        if (substr(LANG_SET, 0, 2) === 'zh') {
            $fullName = $lastName . ' ' . $firstName;
        } else {
            $fullName = $firstName . ' ' . $lastName;
        }

        $msg .= PHP_EOL . '  $fullName = ' . $fullName
            . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $fullName;
    }

    public function login($username, $password)
    {
        $msg = PHP_EOL . 'Home\Model\UserModel::login():'
            . PHP_EOL . '  $username = ' . $username
            . PHP_EOL . '  $password = ' . $password;

        $authenticated = false;

        $user = $this->where(['name' => $username])->find();

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
