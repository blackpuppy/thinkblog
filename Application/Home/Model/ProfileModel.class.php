<?php
namespace Home\Model;

use Home\Model\BaseModel;
use Home\Model\ConfigListModel;

class ProfileModel extends BaseModel
{
    protected $_link = [
        'User' => [
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'User',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'user',
        ],
    ];

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);

        $genders = ConfigListModel::getConfigList(ConfigListModel::LIST_NAME_GENDER);
        $genderKeys = [];
        array_walk($genders, function ($gender) use (&$genderKeys)
        {
           $genderKeys[] = $gender['list_key'];
        });
        // \Think\Log::write('$genderKeys = ' . print_r($genderKeys, true), 'DEBUG');

        $this->_validate = [
            ['first_name', '1,255', '{%FIRST_NAME_LENGTH}', self::EXISTS_VALIDATE, 'length'],
            ['last_name', '0,255', '{%LAST_NAME_LENGTH}', self::EXISTS_VALIDATE, 'length'],
            ['address', 'require', '{%ADDERSS_REQUIRED}', self::VALUE_VALIDATE],
            ['postal_code', '/[^0-9]*/', '{%INVALID_POSTAL_CODE}', self::VALUE_VALIDATE, 'regex'],
            ['gender_key', $genderKeys, '{%INVALID_GENDER}', self::VALUE_VALIDATE, 'in'],
        ];

        $this->_auto[] = ['user_id', 'getCurrentUserId', self::MODEL_INSERT, 'callback'];

        // \Think\Log::write('$this->_auto = ' . print_r($this->_auto, true), 'DEBUG');
    }

    public function fullName()
    {
        // return substr(LANG_SET, 0, 2) === 'zh' ?
        //     $fullName = $this->last_name . ' ' . $this->first_name :
        //     $fullName = $this->first_name . ' ' . $this->last_name;

        return ProfileModel::getFullName($this);
    }

    public static function getFullName(mixed $user, string $lastName = null)
    {
        $msg = 'ProfileModel::getFullName():'
            . PHP_EOL . '  $user = ' . print_r($user, true)
            . PHP_EOL . '  $lastName = ' . $lastName;

        $fullName = "";

        if (is_string($user)) {
            $firstName = $user;
        } elseif (is_array($user)) {
            $firstName = $user['profile']['first_name'];
            $lastName = $user['profile']['last_name'];
        } elseif (is_subclass_of($user, 'ProfileModel')) {
            $firstName = $user->profile->first_name;
            $lastName = $user->profile->last_name;
        }

        if (substr(LANG_SET, 0, 2) === 'zh') {
            $fullName = $lastName . ' ' . $firstName;
        } else {
            $fullName = $firstName . ' ' . $lastName;
        }

        $msg .= PHP_EOL . '  $fullName = ' . $fullName
            . PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');

        return $fullName;
    }
}
