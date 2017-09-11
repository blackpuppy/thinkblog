<?php
namespace Home\Model;

use Home\Model\BaseModel;
use Home\Model\ConfigListModel;

class UserRoleModel extends BaseModel
{
    protected $tableName = 'user_group';

    protected $_link = [
        'User' => [
            'mapping_type'         => self::BELONGS_TO,
            'class_name'           => 'User',
            'mapping_name'         => 'users',
            'foreign_key'          => 'uid',
        ],
        'Role' => [
            'mapping_type'         => self::BELONGS_TO,
            'class_name'           => 'Role',
            'mapping_name'         => 'roles',
            'foreign_key'          => 'group_id',
        ],
    ];
}
