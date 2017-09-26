<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateConfigListTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $this->table('config_list', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => '配置常量'
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键字段'
        ])->addColumn('list_name', 'string', [
            'null' => false,
            'limit' => 64,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量列表名称',
        ])->addColumn('list_key', 'string', [
            'null' => false,
            'limit' => 32,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量的键',
        ])->addColumn('list_value', 'string', [
            'null' => false,
            'limit' => 64,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量的值',
        ])->addColumn('list_value_desc', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量的值的描述',
        ])->addColumn('display_order', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '配置常量在该列表中的显示顺序'
        ])->addColumn('parent_list_name', 'string', [
            'null' => true,
            'limit' => 64,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量的父节点的列表名称',
        ])->addColumn('parent_list_key', 'string', [
            'null' => true,
            'limit' => 32,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '配置常量的父节点的键',
        ])->addColumn('created_by', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '创建用户id'
        ])->addColumn('created_at', 'datetime', [
            'null' => false,
            'default' => 'CURRENT_TIMESTAMP',
            'comment' => '创建时间',
        ])->addColumn('updated_by', 'integer', [
            'null' => true,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '更新用户id'
        ])->addColumn('updated_at', 'datetime', [
            'null' => true,
            'comment' => '更新时间',
        ])->addIndex(['list_name', 'list_key'], [
            'name' => 'idx_list_key',
            'unique' => true,
        ])->addIndex(['list_name', 'parent_list_name', 'parent_list_key', 'display_order'], [
            'name' => 'idx_list_order',
            'unique' => true,
        ])->addForeignKey(['parent_list_name', 'parent_list_key'], 'config_list', ['list_name', 'list_key'], [
            'delete'=> 'RESTRICT',
            'update'=> 'CASCADE',
        ])->create();
    }
}
