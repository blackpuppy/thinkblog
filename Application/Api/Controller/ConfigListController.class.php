<?php
namespace Api\Controller;

use Api\Controller\BaseController;

/**
 * ConfigList API控制器。
 */
class ConfigListController extends BaseController
{
    // REST允许的请求类型列表
    protected $allowMethod = array('get');

    // REST允许请求的资源类型列表
    protected $allowType = array('json');

    // 默认的资源类型
    protected $defaultType = 'json';

    /**
     * 获取配置列表。
     * @return void
     */
    public function index()
    {
        $msg = PHP_EOL . 'Api\Controller\ConfigListApiController::index():';

        try {
            $listName = I('list_name');

            $msg .= PHP_EOL . '  $listName = ' . print_r($listName, true);

            $ConfigList = D('Home/ConfigList');
            $list = $ConfigList->getConfigList($listName);

            // $msg .= PHP_EOL . '  $list = ' . print_r($list, true);

            $this->response($list, 'json');
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }
}
