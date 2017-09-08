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

    /**
     * 按照给定条件查询多条记录。
     *
     * @param  array $parameters  给定的查询参数，{@see PostModel::buildQueryParams()}。
     * @return array 按照给定条件查询得到的多条记录。
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function getMany(array $parameters = [])
    {
        $queryParams = $this->buildQueryParams($parameters);
        extract($queryParams);

        return $this->relation(true)
            ->where($where)
            ->order($order)
            ->select();
    }

    /**
     * 按照给定条件查询记录数量，不考虑分页。
     *
     * @param  array $parameters  给定的查询参数，{@see PostModel::buildQueryParams()}。
     * @return array 按照给定条件查询得到的记录数量。
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function getCount(array $parameters = [])
    {
        $queryParams = $this->buildQueryParams($parameters);
        extract($queryParams);

        return $this->relation(true)
            ->where($where)
            ->count();
    }

    /**
     * 按照给定条件分页查询多条记录。
     *
     * @param  array $parameters  给定的查询参数，{@see PostModel::buildQueryParams()}。
     * @return array 按照给定条件查询得到的多条记录分页结果，有如下键：
     *               - array    $data       查询所得数据
     *               - int      $meta       相关数据(不考虑分页的记录数量、总记录数、总页数)
     *               - string   $pagination 分页链接的HTML
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function paginate(array $parameters = [])
    {
        $msg = PHP_EOL . 'PostModel::paginate():'
            . PHP_EOL . '  $parameters = ' . print_r($parameters, true);

        $queryParams = $this->buildQueryParams($parameters);
        extract($queryParams);

        $count = $this->getCount($parameters);

        $msg .= PHP_EOL . '  $queryParams = ' . print_r($queryParams, true)
            . PHP_EOL . '  $count = ' . $count;

        $parameters = array_filter($parameters);
        $Page = new \Think\Page($count, $pageSize, $parameters);
        $Page->setConfig('header', '共 %TOTAL_ROW% 篇文章');

        // massage $pagination to get a nice pagination data
        $pagination = $this->generatePagination($count, $pageSize, $parameters);

        $data = $this->relation(true)
            ->where($where)
            ->order($order)
            ->page($page, $pageSize)
            ->select();

        $msg .= // PHP_EOL . '  $Page = ' . print_r($Page, true) .
            // . PHP_EOL . '  $data = ' . print_r($data, true)
            PHP_EOL . '  $pagination = ' . print_r($pagination, true);

        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return compact('queryParams', 'data', 'count', 'pagination');
    }

    /**
     * 构建查询参数。
     *
     * @param  array $parameters  给定的查询参数，有如下键：
     *                            - string    $filter   过滤条件，如 field1=value1,field2=value2
     *                            - string    $order    排序，如 field1|asc,field2|desc
     *                            - int|null  $pageSize 每页记录数，如 10, 25
     *                            - int|null  $page     页数
     * @return array 构建的可用于查询的参数数组。
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    public function buildQueryParams(array $parameters = [])
    {
        $msg = PHP_EOL . 'PostModel::buildQueryParams():'
            . PHP_EOL . '  $parameters = ' . print_r($parameters, true);

        $parameters = array_filter($parameters);
        $default = [
            'filter'   => '',
            'order'    => ['id' => 'desc'],
            'pageSize' => C('PAGE_SIZE'),
            'page'     => 1,
        ];
        $params = array_merge($default, $parameters);
        extract($params);

        $msg .= PHP_EOL . '  $default = ' . print_r($default, true)
            . PHP_EOL . '  $parameters = ' . print_r($parameters, true)
            . PHP_EOL . '  $params = ' . print_r($params, true);

        if (!empty($filter)) {
            $where = [];
            $filterTexts = explode(',', $filter);
            foreach ($filterTexts as $filterText) {
                $filterText = trim($filterText);
                $criterion = explode('|', $filterText);

                // $msg .= PHP_EOL . '  $filterText = ' . $filterText;
                // $msg .= PHP_EOL . '  $criterion = ' . print_r($criterion, true);

                $criterion = $this->mapCriterion($criterion);
                if (is_array($criterion) && count($criterion) === 2) {
                    $where[$criterion[0]] = $criterion[1];
                }
            }
        }

        $queryParams = compact('where', 'order', 'pageSize', 'page');

        $msg .= PHP_EOL . '  $queryParams = ' . print_r($queryParams, true);
        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $queryParams;
    }

    /**
     * 映射来自客户端的查询条件为可用于数据库查询的 where 条件。
     * @param  array $criterion 来自客户端的查询条件，格式：['field', 'value]。
     * @return array 可用于数据库查询的 where 条件，格式：['field', 'value]。
     *
     * @author 朱明 <mingzhu.z+gitlab@gmail.com>
     */
    protected function mapCriterion(array $criterion = [])
    {
        $result = false;

        if (count($criterion) === 2) {
            switch ($criterion[0]) {
                case 'author':
                    $result = [
                        'author_user_id',
                        strtolower($criterion[1]) === 'me' ?
                            getCurrentUserId() : $criterion[1]
                    ];
                    break;
                default:
                    $result = [$criterion[0], $criterion[1]];
            }
        }

        return $result;
    }

    protected function generatePagination($count, $pageSize, $parameters)
    {
        $msg = 'PostModel::generatePagination():'
            . PHP_EOL . '  $count = ' . $count
            . PHP_EOL . '  $pageSize = ' . $pageSize
            . PHP_EOL . '  $parameters = ' . print_r($parameters, true);

        $Page = new \Think\Page($count, $pageSize, $parameters);
        $Page->setConfig('prev', urlencode('<<'));

        $themes = [
            // 'header' => '%HEADER%',
            'currentPage' => '%NOW_PAGE%',
            'previous' => '%UP_PAGE%',
            'next' => '%DOWN_PAGE%',
            'first' => '%FIRST%',
            'pages' => '%LINK_PAGE%',
            'last' => '%END%',
            'totalCount' => '%TOTAL_ROW%',
            'totalPages' => '%TOTAL_PAGE%',
        ];

        $pagination = [];
        foreach ($themes as $key => $theme) {
            $pagination[$key] = $this->generateThemedPagination($Page, $theme);
        }

        $meta = [
            'currentPage' => $pagination['currentPage'],
            'totalCount'  => $pagination['totalCount'],
            'totalPages'  => $pagination['totalPages'],
        ];
        $links = [
            'previous'  => $pagination['previous'],
            'next'      => $pagination['next'],
            'first'     => $pagination['first'],
            'pages'     => $pagination['pages'],
            'last'      => $pagination['last'],
        ];

        // $msg .= PHP_EOL . '  $links = ' . print_r($links, true);

        $links = array_filter($links);

        $msg .= PHP_EOL . '  $links = ' . print_r($links, true);

        foreach ($links as $key => $link) {
            // $msg .= PHP_EOL . "  $key => $link";

            $html = "<div>$link</div>";
            $xml = new \SimpleXMLElement($html);

            // $msg .= PHP_EOL . '  $xml = ' . print_r($xml, true);

            if ($key !== 'pages') {
                // $msg .= PHP_EOL . '  $xml->a = ' . print_r($xml->a, true);
                // $msg .= PHP_EOL . '  $xml->a[href] = ' . (string)$xml->a['href'];

                $links[$key] = (string)$xml->a['href'];
            } else {
                $links['pages'] = [];
                foreach ($xml->a as $k => $a) {
                    // $msg .= PHP_EOL . '  $a = ' . (string)$a;
                    // $msg .= PHP_EOL . '  $a[href] = ' . (string)$a['href'];

                    $links['pages'][(string)$a] = (string)$a['href'];
                }
            }
        }

        $pagination = compact('meta', 'links');

        // $msg .= PHP_EOL . '  $pagination = ' . print_r($pagination, true);
        // $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $pagination;
    }

    protected function generateThemedPagination($Page, $theme)
    {
        if (is_string($theme) && !empty($theme)){
            $Page->setConfig('theme', $theme);
        }

        $pagination = $Page->show();

        if (startsWith($pagination, '<div>')
            && endsWith($pagination, '</div>')
        ) {
            $pagination = substr($pagination, 5, -6);
        }

        // \Think\Log::write(PHP_EOL . 'generateThemedPagination() returns ' . print_r($pagination, true), 'DEBUG');

        return $pagination;
    }
}
