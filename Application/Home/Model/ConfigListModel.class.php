<?php
namespace Home\Model;

use Home\Model\BaseModel;

class ConfigListModel extends BaseModel
{
    protected $_validate = [
        ['list_name', 'require', '{%TITLE_REQUIRED}'],
        ['list_key', 'require', '{%TITLE_REQUIRED}'],
        ['list_value', 'require', '{%CONTENT_REQUIRED}', self::VALUE_VALIDATE],
        ['list_value_desc', 'require', '{%CONTENT_REQUIRED}', self::VALUE_VALIDATE],
        ['display_order', 'require', '{%TITLE_REQUIRED}'],
    ];

    const LIST_NAME_GENDER = 'gender';
    const LIST_KEY_GENDER_MALE = 'male';
    const LIST_KEY_GENDER_FEMALE = 'female';

    public static function getConfigList($listName)
    {
        return D('ConfigList')
            ->where(['list_name' => $listName])
            ->order(['display_order' => 'asc'])
            ->select();
    }
}
