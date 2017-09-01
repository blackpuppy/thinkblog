<?php

// 载入环境配置，供getenv()使用
$envFilePath = dirname(__DIR__) . '/.env';
if (file_exists($envFilePath)) {
    $Loader = (new josegonzalez\Dotenv\Loader($envFilePath))
                  ->parse()
                  ->putenv(true);
}

$isAzure = false;
$dbHost = "";
$dbUser = "";
$dbPwd = "";

// 解析 Azure MySQL in App 数据库连接字符串
foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }

    $isAzure = true;
    $dbHost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    // $dbName = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $dbUser = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $dbPwd = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}

if (!$isAzure) {
    $dbHost = getenv('DB_HOST');
    $dbUser = getenv('DB_NAME');
    $dbPwd = getenv('DB_PWD');
}

date_default_timezone_set(getenv('DEFAULT_TIMEZONE'));

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/seeds',
    ],
    'environments' => [
        'default_database' => 'default',
        'default_migration_table' => 'phinxlog',
        'default' => [
            'adapter'       => getenv('DB_TYPE'),
            'host'          => $dbHost,
            'name'          => getenv('DB_NAME'),
            'user'          => $dbUser,
            'pass'          => $dbPwd,
            'port'          => getenv('DB_PORT'),
            'charset'       => getenv('DB_CHARSET'),
            'collation'     => getenv('DB_COLLATION'),
            'table_prefix'  => getenv('DB_PREFIX'),
        ],
    ],
];
