<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePasswordResetTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $userTableName = getenv('AUTH_USER') ?: 'user';

        $this->table('password_reset', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'comment' => '重置密码'
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键'
        ])->addColumn('user_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '用户id'
        ])->addColumn('name', 'string', [
            'null' => false,
            'limit' => 63,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '用户名',
        ])->addColumn('email', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '电子邮箱地址',
        ])->addColumn('token', 'string', [
            'null' => false,
            'limit' => 127,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '重置密码令牌',
        ])->addColumn('token_expired_at', 'datetime', [
            'null' => false,
            'comment' => '重置密码令牌过期时间',
        ])->addColumn('reset_at', 'datetime', [
            'null' => true,
            'comment' => '重置密码时间',
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
        ])->addIndex(['user_id'], [
            'name' => 'idx_user_id',
            'unique' => false,
        ])->addForeignKey('user_id', $userTableName, 'id', [
            'delete'=> 'RESTRICT',
            'update'=> 'CASCADE',
        ])->create();
    }
}
