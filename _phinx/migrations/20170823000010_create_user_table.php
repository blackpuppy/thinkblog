<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUserTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $tableName = getenv('AUTH_USER') ?: 'user';
        $this->table($tableName, [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'comment' => '用户'
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键'
        ])->addColumn('name', 'string', [
            'null' => false,
            'limit' => 63,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '用户名',
        ])->addColumn('password', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '经过加密的密码',
        ])->addColumn('email', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '电子邮箱地址',
        ])->addColumn('remember_token', 'string', [
            'null' => true,
            'limit' => 127,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '记住我令牌',
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
        ])->addIndex(['name'], [
            'name' => 'idx_name',
            'unique' => true
        ])->addIndex(['email'], [
            'name' => 'idx_email',
            'unique' => true
        ])->create();
    }
}
