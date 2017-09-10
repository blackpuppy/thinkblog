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
            'foreign_key'          => 'role_id',
            'relation_foreign_key' => 'user_id',
            'relation_table'       => 'user_group',
        ],
    ];
}
