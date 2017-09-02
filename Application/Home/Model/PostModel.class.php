<?php
namespace Home\Model;

use Home\Model\BaseModel;

class PostModel extends BaseModel
{
    protected $_validate = [
        ['title', 'require', '{%TITLE_REQUIRED}'],
        ['content', 'require', '{%CONTENT_REQUIRED}'],
    ];

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);

        $this->_auto = [
            ['author_user_id', 'getCurrentUserId', self::MODEL_INSERT, 'callback'],
        ];
    }

    public function getCurrentUserId()
    {
        $isAuthenticated = session('?authentication.authenticated')
                        && session('authentication.authenticated');
        $currentUserId = $isAuthenticated ? session('authentication.user')['id'] : 0;
        return $currentUserId;
    }
}
