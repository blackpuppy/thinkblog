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

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);

        $this->_auto[] =
            ['author_user_id', 'getCurrentUserId', self::MODEL_INSERT, 'function'];
    }

    public function getMany(array $parameters = [])
    {
        $queryParams = $this->buildQueryParams($parameters);
        extract($queryParams);
        return $this->relation(true)
            ->where($where)
            ->order($order)
            ->select();
    }

    public function count(array $parameters = [])
    {
    }

    public function paginate(array $parameters = [])
    {
    }

    /**
     * 构建查询参数.
     *
     * @param  array $parameters  给定的查询参数，有如下键：
     *                            - string    $filter  过滤条件，如 field1=value1,field2=value2
     *                            - string    $order   排序，如 field1|asc,field2|desc
     *                            - int|null  $perPage 每页记录数，如 10, 25
     *                            - int|null  $page    页数
     * @return array 构建的可用于查询的参数数组。
     *
     * @author Zhu Ming <mingzhu.z+gitlab@gmail.com>
     */
    public function buildQueryParams(array $parameters = [])
    {
        $default = [
            'filter' => '',
            'order' => ['id' => 'desc'],
            'perPage' => 10,
            'page' => 1,
        ];
        $params = array_merge($default, $parameters);
        extract($params);

        if (!empty($filter)) {
            $where = [];
            $filterTexts = explode(',', $filter);
            foreach ($filterTexts as $filterText) {
                $filterText = trim($filterText);
                $tokens = explode('|', $filterText);

                $msg .= PHP_EOL . '  $filterText = ' . $filterText;
                $msg .= PHP_EOL . '  $tokens = ' . print_r($tokens, true);

                if (count($tokens) === 2) {
                    switch ($tokens[0]) {
                        case 'author':
                            $where['author_user_id'] = strtolower($tokens[1]) === 'me' ?
                                getCurrentUserId() : $tokens[1];
                            break;
                        default:
                            $where[$tokens[0]] = $tokens[1];
                    }
                }
            }
        }

        $queryParams = compact('where', 'order', 'perPage', 'page');

        return $queryParams;
    }
}
