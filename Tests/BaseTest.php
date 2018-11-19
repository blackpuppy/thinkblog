<?php
namespace Tests;

use josegonzalez\Dotenv\Loader;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 日志
     *
     * @var Monolog\Logger
     *
     * @author Zhu Ming <mingzhu.z+gitlab@gmail.com>
     */
    protected $logger;

    /**
     * Constructs a test case with the given name.
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        self::loadDotEnv();

        $this->ensureLogger();
    }

    protected function ensureLogger()
    {
        // create a log channel for Monolog
        if (!isset($this->logger)) {
            $messageFormat = getenv('UNIT_TEST_LOG_MESSAGE_FORMAT') . PHP_EOL;
            $dateFormat    = getenv('UNIT_TEST_LOG_DATE_FORMAT');
            $formatter     = new LineFormatter($messageFormat, $dateFormat, true, true);

            $handler = new StreamHandler(
                __DIR__ . '/Logs/test.log',
                Logger::DEBUG
            );
            $handler->setFormatter($formatter);

            $this->logger = new Logger('eMenu');
            $this->logger->pushHandler($handler);
        }
    }

    protected static function loadDotEnv()
    {
        // 载入环境配置
        $envFilePath = dirname(__DIR__) . '/.env';

        // echo '$envFilePath = ' . $envFilePath . PHP_EOL;

        if (file_exists($envFilePath)) {
            $Loader = (new Loader($envFilePath))
                          ->parse()
                          ->putenv(true)
                          ->toEnv(true);
        }

        define('APP_DEBUG', (bool) getenv('APP_DEBUG'));
        define('ROOT_PATH', dirname(__DIR__));
        define('APP_PATH', dirname(__DIR__) . '/Application/');
        define('RUNTIME_PATH', dirname(__DIR__) . '/Application/Runtime/');
        define('LIB_3RD_PARTY_PATH', dirname(__DIR__) . '/lib/');
        define('THINK_PATH', dirname(__DIR__) . '/vendor/topthink/thinkphp/ThinkPHP/');

        // define('STORAGE_TYPE', 'File');

        // if (class_exists('Snowair\Think\Behavior\HookAgent')) {
        //     echo 'hook app_begin to Snowair\\Dotenv\\DotEnv' . PHP_EOL;

        //     \Snowair\Think\Behavior\HookAgent::add('app_begin', 'Snowair\\Dotenv\\DotEnv');
        // }

        // echo 'APP_DEBUG = ' . APP_DEBUG . PHP_EOL;
        // echo 'ROOT_PATH = ' . ROOT_PATH . PHP_EOL;
        // echo 'APP_PATH = ' . APP_PATH . PHP_EOL;
        // echo 'THINK_PATH = ' . THINK_PATH . PHP_EOL;
        // echo 'RUNTIME_PATH = ' . RUNTIME_PATH . PHP_EOL;
        // echo 'STORAGE_TYPE = ' . STORAGE_TYPE . PHP_EOL;
    }
}
