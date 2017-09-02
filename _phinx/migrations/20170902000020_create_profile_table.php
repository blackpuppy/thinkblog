<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateProfileTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $this->table('profile', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'comment' => '用户资料'
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键字段'
        ])->addColumn('user_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '用户id'
        ])->addColumn('first_name', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '名',
        ])->addColumn('last_name', 'string', [
            'null' => true,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '姓',
        ])->addColumn('phone', 'string', [
            'null' => true,
            'limit' => 127,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '电话号码',
        ])->addColumn('address', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '地址',
        ])->addColumn('postal_code', 'string', [
            'null' => false,
            'limit' => 16,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '邮政编码',
        ])->addColumn('gender_key', 'string', [
            'null' => false,
            'limit' => 32,
            'collation' => 'utf8_unicode_ci',
            'encoding' => 'utf8',
            'comment' => '性别'
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
            'unique' => true,
        ])->addForeignKey('user_id', 'user', 'id', [
            'delete'=> 'RESTRICT',
            'update'=> 'CASCADE',
        ])->create();
    }
}
