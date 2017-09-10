<?php
namespace Home\Model;

use Home\Model\BaseModel;
use Home\Model\ConfigListModel;

class RoleModel extends BaseModel
{
    protected $_link = [
        'User' => [
            'mapping_type'         => self::MANY_TO_MANY,
            'class_name'           => 'User',
            'mapping_name'         => 'users',
            'foreign_key'          => 'group_id',
            'relation_foreign_key' => 'uid',
            'relation_table'       => 'user_group',
        ],
    ];
}
