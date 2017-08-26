<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAuthRuleTables extends AbstractMigration
{
    public function change()
    {
        $this->table('auth_rule', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => '规则表',
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键',
        ])->addColumn('name', 'char', [
            'null' => false,
            'default' => '',
            'limit' => 80,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '规则唯一标识',
        ])->addColumn('title', 'char', [
            'null' => false,
            'default' => '',
            'limit' => 20,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '规则中文名称',
        ])->addColumn('type', 'boolean', [
            'null' => false,
            'default' => '1',
            'limit' => MysqlAdapter::INT_TINY,
            'precision' => 3,
            'comment' => '',
        ])->addColumn('status', 'boolean', [
            'null' => false,
            'default' => '1',
            'limit' => MysqlAdapter::INT_TINY,
            'precision' => 3,
            'comment' => '状态：为1正常，为0禁用',
        ])->addColumn('condition', 'char', [
            'null' => false,
            'default' => '',
            'limit' => 100,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '规则附件条件，为空表示存在就验证，不为空表示按照条件验证。满足附加条件的规则，才认为是有效的规则',
        ])->addColumn('created_at', 'datetime', [
            'null' => false,
            'default' => 'CURRENT_TIMESTAMP',
            'comment' => '创建时间',
        ])->addColumn('updated_at', 'datetime', [
            'null' => true,
            'comment' => '更新时间',
        ])->addIndex(['name'], [
            'name' => 'name',
            'unique' => true
        ])->create();
    }
}
