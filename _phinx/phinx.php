<?php

// 载入环境配置，供getenv()使用
$Loader = (new josegonzalez\Dotenv\Loader(dirname(__DIR__) . '/.env'))
              ->parse()
              ->putenv(true);

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
            'host'          => getenv('DB_HOST'),
            'name'          => getenv('DB_NAME'),
            'user'          => getenv('DB_USER'),
            'pass'          => getenv('DB_PWD'),
            'port'          => getenv('DB_PORT'),
            'charset'       => getenv('DB_CHARSET'),
            'collation'     => getenv('DB_COLLATION'),
            'table_prefix'  => getenv('DB_PREFIX'),
        ],
    ],
];
