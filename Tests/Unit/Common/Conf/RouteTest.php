<?php
namespace Tests\Unit\Common\Conf;

use Tests\BaseTest;
use Think\DatabaseTransaction;
use Think\Dispatcher;
use Think\Phpunit;
use Think\PhpunitHelper;

class RouteTest extends BaseTest
{
    use PhpUnit; // 只有控制器测试类才需要它
    use DatabaseTransaction;

    protected $msg = '';

    public static function setupBeforeClass()
    {
        $webrootUrl = getenv('UNIT_TEST_WEBROOT_URL');

        // 在PhpunitHelper之前先定义THINK_VERSION
        define('THINK_VERSION', '3.2.3');

        // 下面四行代码模拟出一个应用实例, 每一行都很关键, 需正确设置参数
        self::$app = new PhpunitHelper(APP_PATH, THINK_PATH);

        // self::$app->setMVC($webrootUrl, 'Home', 'Index');
        self::$app->setMVC($webrootUrl, 'NoModule', 'NoController');

        self::$app->setTestConfig([
            'DB_TYPE'    => getenv('DB_TYPE'),    // 数据库类型
            'DB_HOST'    => getenv('DB_HOST'),    // 服务器地址
            'DB_NAME'    => getenv('TEST_DB_NAME'),    // 测试数据库名
            'DB_USER'    => getenv('DB_USER'),    // 用户名
            'DB_PWD'     => getenv('DB_PWD'),     // 密码
            'DB_PORT'    => getenv('DB_PORT'),    // 端口
            'DB_PREFIX'  => getenv('DB_PREFIX'),  // 数据库表前缀
            'DB_CHARSET' => getenv('DB_CHARSET'), // 字符集
        ]); // 一定要设置一个测试用的数据库,避免测试过程破坏生产数据
        self::$app->start();
    }

    /**
     * 测试路由
     */
    public function testRoutes()
    {
        $this->msg = 'RouteTest::testRoutes():';
        $this->msg .= PHP_EOL . 'ThinkPHP版本: THINK_VERSION = ' . getConstant('THINK_VERSION');

        // $this->msg .= PHP_EOL . 'APP_DEBUG = ' . getConstant('APP_DEBUG');
        // $this->msg .= PHP_EOL . 'ROOT_PATH = ' . getConstant('ROOT_PATH');
        // $this->msg .= PHP_EOL . 'APP_PATH = ' . getConstant('APP_PATH');
        // $this->msg .= PHP_EOL . 'THINK_PATH = ' . getConstant('THINK_PATH');
        // $this->msg .= PHP_EOL . 'RUNTIME_PATH = ' . getConstant('RUNTIME_PATH');
        // $this->msg .= PHP_EOL . 'URL_PATHINFO_DEPR = ' . getConstant('URL_PATHINFO_DEPR');

        // $this->msg .= PHP_EOL . 'STORAGE_TYPE = ' . getConstant('STORAGE_TYPE');

        // $this->msg .= PHP_EOL . 'DB_TYPE = ' . getConstant('DB_TYPE');
        // $this->msg .= PHP_EOL . 'DB_NAME = ' . getConstant('DB_NAME');
        // $this->msg .= PHP_EOL . 'TEST_DB_NAME = ' . getConstant('TEST_DB_NAME');

        // $this->msg .= PHP_EOL . 'getenv(DB_TYPE) = ' . getenv('DB_TYPE');
        // $this->msg .= PHP_EOL . 'getenv(DB_HOST) = ' . getenv('DB_HOST');
        // $this->msg .= PHP_EOL . 'getenv(DB_NAME) = ' . getenv('DB_NAME');
        // $this->msg .= PHP_EOL . 'getenv(TEST_DB_NAME) = ' . getenv('TEST_DB_NAME');

        // $this->msg .= PHP_EOL . 'MODULE_NAME = ' . getConstant('MODULE_NAME')
        //     . PHP_EOL . 'CONTROLLER_NAME = ' . getConstant('CONTROLLER_NAME')
        //     . PHP_EOL . 'ACTION_NAME = ' . getConstant('ACTION_NAME');

        // $this->msg .= PHP_EOL . 'eMenu 配置:'
        //     . PHP_EOL . '  C(DEMO_COMPANY_ID_LIST) = ' . print_r(C('DEMO_COMPANY_ID_LIST'), true);

        try {
            $this->msg .= PHP_EOL . '测试' . count(C('URL_MAP_RULES')) . '个静态路由 ...';

            foreach (C('URL_MAP_RULES') as $rule => $route) {
                $this->_testRoute($rule, $route);
            }

            $this->msg .= PHP_EOL . PHP_EOL . '测试' . count(C('URL_ROUTE_RULES')) . '个动态路由 ...';

            foreach (C('URL_ROUTE_RULES') as $rule => $route) {
                $this->_testRoute($rule, $route);
            }
        } finally {
            $this->msg .= PHP_EOL . str_repeat('-', 80);
            $this->logger->debug($this->msg);
            $this->msg = '';
        }
    }

    protected function _testRoute($rule, $route)
    {
        if (is_numeric($rule)) {
            $rule = array_shift($route);
        }

        $this->msg .= PHP_EOL . PHP_EOL . "$rule => " . print_r($route, true);

        // if (strpos($rule, ':')) {   // 先不测试存在动态变量的路由
        //     return;
        // }

        // 替换路由参数为实际参数
        $rule = str_replace('/:id\d', '/123', $rule);

        $rule   = "/$rule";
        $method = 'GET';
        if (is_string($route)) {
            $routeAddress = $route;
        } elseif (is_array($route)) {
            $routeAddress = $route[0];
            if (isset($route[2]) && isset($route[2]['method'])) {
                $method = strtoupper($route[2]['method']);
            }
        }

        // $this->msg .= PHP_EOL . "  THINK_VERSION = " . THINK_VERSION;

        if (version_compare(THINK_VERSION, '3.2.3', '<=')) {
            $actual = $this->_parse323Route($rule, $method);
        } else {
            $actual = $this->_parse324Route($rule, $method);
        }
        extract($actual);

        list($expectedModule, $expectedController, $expectedAction) = explode('/', $routeAddress, 3);

        $this->msg .= PHP_EOL . "  expected: $expectedModule/$expectedController/$expectedAction";
        $this->msg .= PHP_EOL . "  actual  : $moduleName/$controllerName/$actionName";

        $this->assertEquals($expectedModule, $moduleName);
        $this->assertEquals($expectedController, $controllerName);
        $this->assertEquals($expectedAction, $actionName);
    }

    protected function _parse323Route($urlPath, $method)
    {
        $_SERVER['PATH_INFO']      = $urlPath;
        $_SERVER['REQUEST_METHOD'] = $method;

        // $this->msg .= PHP_EOL . '  $_SERVER = ' . print_r($_SERVER, true);
        // $this->msg .= PHP_EOL . '  $_SERVER[PATH_INFO] = ' . $_SERVER['PATH_INFO'];

        defineConstant('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        defineConstant('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        defineConstant('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
        defineConstant('IS_PUT', REQUEST_METHOD == 'PUT' ? true : false);
        defineConstant('IS_DELETE', REQUEST_METHOD == 'DELETE' ? true : false);

        $varModule     = C('VAR_MODULE');
        $varController = C('VAR_CONTROLLER');
        $varAction     = C('VAR_ACTION');
        $urlCase       = C('URL_CASE_INSENSITIVE');

        // URL后缀
        defineConstant('__EXT__', strtolower(pathinfo($_SERVER['PATH_INFO'], PATHINFO_EXTENSION)));
        $_SERVER['PATH_INFO'] = trim($_SERVER['PATH_INFO'], '/');

        // $this->msg .= PHP_EOL . '  MODULE_PATHINFO_DEPR = ' . getConstant('MODULE_PATHINFO_DEPR');

        // simulate 3.2.3 Dispatcher::dispatch()

        // Route::check()
        $routeCheckResult = $this->_callPrivateStaticMethod('Think\Route', 'check', []);

        // $this->msg .= PHP_EOL . '  $routeCheckResult = ' . $routeCheckResult;
        // $this->msg .= PHP_EOL . '  $_GET[$varModule] = ' . $_GET[$varModule];
        // $this->msg .= PHP_EOL . '  $_GET[$varController] = ' . $_GET[$varController];
        // $this->msg .= PHP_EOL . '  $_GET[$varAction] = ' . $_GET[$varAction];

        // $moduleName = Dispatcher::getModule($paths)
        $moduleName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getModule', [$varModule]);

        // $controllerName = Dispatcher::getController($paths, $urlCase)
        $controllerName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getController', [$varController, $urlCase]);

        // Dispatcher::getAction($paths, $urlCase));
        $actionName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getAction', [$varAction, $urlCase]);

        // $this->msg .= PHP_EOL . '  $moduleName = ' . $moduleName
        //     . PHP_EOL . '  $controllerName = ' . $controllerName
        //     . PHP_EOL . '  $actionName = ' . $actionName;

        return compact(['moduleName', 'controllerName', 'actionName']);
    }

    protected function _parse324Route($urlPath, $method)
    {
        $_SERVER['PATH_INFO']      = $urlPath;
        $_SERVER['REQUEST_METHOD'] = $method;

        // $this->msg .= PHP_EOL . '  $_SERVER = ' . print_r($_SERVER, true);
        // $this->msg .= PHP_EOL . '  $_SERVER[PATH_INFO] = ' . $_SERVER['PATH_INFO'];

        defineConstant('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        defineConstant('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        defineConstant('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
        defineConstant('IS_PUT', REQUEST_METHOD == 'PUT' ? true : false);
        defineConstant('IS_DELETE', REQUEST_METHOD == 'DELETE' ? true : false);

        $urlCase = C('URL_CASE_INSENSITIVE');

        $depr = C('URL_PATHINFO_DEPR');
        // defineConstant('MODULE_PATHINFO_DEPR', $depr);

        // URL后缀
        defineConstant('__EXT__', strtolower(pathinfo($_SERVER['PATH_INFO'], PATHINFO_EXTENSION)));
        // 检查禁止访问的URL后缀
        if ($denySuffix = C('URL_DENY_SUFFIX')) {
            if (in_array(__EXT__, explode('|', strtolower(str_replace('.', '', $denySuffix))))) {
                $this->msg .= PHP_EOL . '  ' . __EXT__ . ' in URL_DENY_SUFFIX(=' . print_r($denySuffix, true) . ')';

                //send_http_status(404);
                exit;
            }
        }
        // defineConstant('__INFO__', trim($_SERVER['PATH_INFO'], '/'));
        // 去除URL后缀
        $_SERVER['PATH_INFO'] = preg_replace('/\.' . __EXT__ . '$/i', '', trim($_SERVER['PATH_INFO'], '/'));
        $paths                = explode($depr, trim($_SERVER['PATH_INFO'], $depr));

        // $this->msg .= PHP_EOL . '  MODULE_PATHINFO_DEPR = ' . getConstant('MODULE_PATHINFO_DEPR');
        // $this->msg .= PHP_EOL . '  before checking: $paths = ' . print_r($paths, true);

        // Route::check()
        // $routeCheckResult = $this->_callPrivateStaticMethod('Think\Route', 'check', [$paths]);

        // $this->msg .= PHP_EOL . '  $routeCheckResult = ' . $routeCheckResult;

        // simulate 3.2.5 Dispatcher::dispatch()

        // $moduleName = Dispatcher::getModule($paths)
        $moduleName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getModule', [&$paths]);

        // $controllerName = Dispatcher::getController($paths, $urlCase)
        $controllerName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getController', [&$paths, $urlCase]);

        // Dispatcher::getAction($paths, $urlCase));
        $actionName = $this->_callPrivateStaticMethod('Think\Dispatcher', 'getAction', [&$paths, $urlCase]);

        // $this->msg .= PHP_EOL . '  $moduleName = ' . $moduleName
        //     . PHP_EOL . '  $controllerName = ' . $controllerName
        //     . PHP_EOL . '  $actionName = ' . $actionName;

        return compact(['moduleName', 'controllerName', 'actionName']);
    }

    protected function _callPrivateStaticMethod($class, $method, $args)
    {
        $method = getMethod($class, $method);
        return $method->invokeArgs(null, $args);
    }
}
