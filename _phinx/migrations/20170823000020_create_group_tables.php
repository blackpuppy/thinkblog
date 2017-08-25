<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGroupTables extends AbstractMigration
{
    public function change()
    {
        $this->table('group', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => '用户组表',
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键',
        ])->addColumn('title', 'char', [
            'null' => false,
            'default' => '',
            'limit' => 100,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '用户组中文名称',
        ])->addColumn('status', 'boolean', [
            'null' => false,
            'default' => '1',
            'limit' => MysqlAdapter::INT_TINY,
            'precision' => 3,
            'comment' => '状态：为1正常，为0禁用',
        ])->addColumn('rules', 'char', [
            'null' => false,
            'default' => '',
            'limit' => 80,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '用户组拥有的规则id， 多个规则用","隔开',
        ])->addColumn('created_at', 'datetime', [
            'null' => false,
            'default' => 'CURRENT_TIMESTAMP',
            'comment' => '创建时间',
        ])->addColumn('updated_at', 'datetime', [
            'null' => true,
            'comment' => '更新时间',
        ])->create();
    }
}
