<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUserGroupTables extends AbstractMigration
{
    public function change()
    {
        $this->table('user_group', [
            'id' => false,
            'primary_key' => ['uid', 'group_id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => '用户-用户组关系表',
        ])->addColumn('uid', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '用户id',
        ])->addColumn('group_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '用户组id',
        ])->addIndex(['uid', 'group_id'], [
            'name' => 'uid_group_id',
            'unique' => true
        ])->addIndex(['uid'], [
            'name' => 'uid',
            'unique' => false
        ])->addIndex(['group_id'], [
            'name' => 'group_id',
            'unique' => false
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
