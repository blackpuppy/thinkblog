<?php
namespace Home\Model;

use Think\Model;

class PostModel extends Model
{
    protected $_validate = [
        ['title', 'require', '{%TITLE_REQUIRED}'],
        ['content', 'require', '{%CONTENT_REQUIRED}'],
    ];

    protected $_auto = [
    	['created_at', 'getNow', self::MODEL_INSERT, 'callback'],
    	['updated_at', 'getNow', self::MODEL_UPDATE, 'callback'],
    ];

    public function getNow()
    {
    	return date('Y-m-d H:i:s.u');
    }
}
