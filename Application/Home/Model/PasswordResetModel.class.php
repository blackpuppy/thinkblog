<?php
namespace Home\Model;

use Home\Model\BaseModel;

class PasswordResetModel extends BaseModel
{
    protected $_validate = [
        ['name',  'require',       '{%NAME_REQUIRED}',    self::MUST_VALIDATE],
        ['name',  'checkUsername', '{%NAME_NOT_EXISTS}',  self::MUST_VALIDATE, 'callback'],
        ['email', 'require',       '{%EMAIL_REQUIRED}',   self::MUST_VALIDATE],
        ['email', 'checkEmail',    '{%EMAIL_NOT_EXISTS}', self::MUST_VALIDATE, 'callback'],
        ['email',
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            '{%EMAIL_INVALID}', self::EXISTS_VALIDATE, 'regex'],
    ];

    protected $_link = [
        'User' => [
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'User',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'user',
        ],
    ];

    /**
     * 包含敏感信息的属性/字段。
     */
    protected $_sensitive = [
        'token',
    ];

    /**
     * 验证用户名是否存在。
     *
     * @param array $name 给定用户名
     * @return bool       给定用户名是否存在
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function checkUsername($name)
    {
        $msg = "PasswordResetModel::checkUsername():"
            . PHP_EOL . '  $name = ' . $name;

        $User = D('User');
        $count = $User->where(['name' => $name])->count();
        $valid = $count == 1;

        $msg .= PHP_EOL . '  $count = ' . $count
            . PHP_EOL . '  $valid = ' . $valid
            . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $valid;
    }

    /**
     * 验证电子邮箱是否存在。
     *
     * @param array $email 给定电子邮箱
     * @return bool        给定电子邮箱是否存在
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function checkEmail($email)
    {
        $msg = "PasswordResetModel::checkEmail():"
            . PHP_EOL . '  $email = ' . $email;

        $User = D('User');
        $count = $User->where(['email' => $email])->count();
        $valid = $count == 1;

        $msg .= PHP_EOL . '  $count = ' . $count
            . PHP_EOL . '  $valid = ' . $valid
            . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $valid;
    }
}
