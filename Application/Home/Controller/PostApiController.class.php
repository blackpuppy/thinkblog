<?php
namespace Home\Controller;

use Think\Controller\RestController;
use Think\Model;

/**
 * 文章API控制器。
 */
class PostApiController extends RestController
{
    // REST允许的请求类型列表
    protected $allowMethod = array('get', 'post', 'put', 'delete');

    // REST允许请求的资源类型列表
    protected $allowType = array('json');

    // 默认的资源类型
    protected $defaultType = 'json';

    /**
     * 文章列表。
     * @return void
     */
    public function index()
    {
        $msg = PHP_EOL . 'Home\Controller\PostApiController::index():';

        try {
            $filter = I('filter');
            $order = I('order');
            $page = I(C('VAR_PAGE'));
            $pageSize = I('pageSize');
            $parameters = compact('filter', 'order', 'page', 'pageSize');

            $msg .= // PHP_EOL . '  VAR_PAGE = ' . C('VAR_PAGE') .
                PHP_EOL . '  parameters = ' . print_r($parameters, true);

            $Post = D('Post');
            $posts = $Post->paginate($parameters);

            // $msg .= PHP_EOL . '  $posts = ' . print_r($posts, true);

            $this->response($posts, 'json');
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }
}
