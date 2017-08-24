<?php
namespace Home\Model;

use Think\Model\AdvModel;

abstract class BaseModel extends AdvModel
{
    protected $_auto = [
        ['created_at', 'getNow', self::MODEL_INSERT, 'callback'],
        ['updated_at', 'getNow', self::MODEL_UPDATE, 'callback'],
    ];

    public function getNow()
    {
        return date('Y-m-d H:i:s.u');
    }
}
