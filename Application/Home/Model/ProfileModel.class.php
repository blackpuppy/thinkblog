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

    public function getFullName()
    {
        return getUserFullName($this);
    }
}
