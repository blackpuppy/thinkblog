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
            ['first_name',  'require',   '{%FIRST_NAME_REQUIRED}',  self::EXISTS_VALIDATE],
            ['first_name',  '1,255',     '{%FIRST_NAME_LENGTH}',    self::EXISTS_VALIDATE, 'length'],
            ['last_name',   'require',   '{%LAST_NAME_REQUIRED}',   self::EXISTS_VALIDATE],
            ['last_name',   '1,255',     '{%LAST_NAME_LENGTH}',     self::EXISTS_VALIDATE, 'length'],
            ['address',     'require',   '{%ADDERSS_REQUIRED}',     self::EXISTS_VALIDATE],
            ['postal_code', 'require',   '{%POSTAL_CODE_REQUIRED}', self::EXISTS_VALIDATE],
            ['postal_code', '/[^0-9]*/', '{%INVALID_POSTAL_CODE}',  self::EXISTS_VALIDATE, 'regex'],
            ['gender_key',  $genderKeys, '{%INVALID_GENDER}',       self::VALUE_VALIDATE,  'in'],
        ];

        $this->_auto[] = ['user_id', 'getCurrentUserId', self::MODEL_INSERT, 'function'];

        // \Think\Log::write('ProfileModel::__construct(): $this->_auto = ' . print_r($this->_auto, true), 'DEBUG');
    }

    public function getFullName()
    {
        return getUserFullName($this);
    }
}
