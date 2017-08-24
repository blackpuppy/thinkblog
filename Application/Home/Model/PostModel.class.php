<?php
namespace Home\Model;

use Home\Model\BaseModel;

class PostModel extends BaseModel
{
    protected $_validate = [
        ['title', 'require', '{%TITLE_REQUIRED}'],
        ['content', 'require', '{%CONTENT_REQUIRED}'],
    ];
}
