<?php
namespace Home\Model;

use Home\Model\BaseModel;

class PostModel extends BaseModel
{
    protected $_validate = [
        ['title', 'require', '{%TITLE_REQUIRED}'],
        ['content', 'require', '{%CONTENT_REQUIRED}'],
    ];

    protected $_link = [
        'User' => [
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'User',
            'foreign_key'   => 'author_user_id',
            'mapping_name'  => 'author',
        ],
    ];

    protected $_auto = [
        ['created_by',     'getCurrentUserId', self::MODEL_INSERT, 'callback'],
        ['created_at',     'getNow',           self::MODEL_INSERT, 'callback'],
        ['updated_by',     'getCurrentUserId', self::MODEL_UPDATE, 'callback'],
        ['updated_at',     'getNow',           self::MODEL_UPDATE, 'callback'],
        ['author_user_id', 'getCurrentUserId', self::MODEL_INSERT, 'callback'],
    ];
}
