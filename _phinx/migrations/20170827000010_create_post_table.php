<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePostTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $this->table('post', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => '文章'
        ])->addColumn('id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'identity' => 'enable',
            'comment' => '主键字段'
        ])->addColumn('title', 'string', [
            'null' => false,
            'limit' => 255,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '标题',
        ])->addColumn('content', 'text', [
            'null' => false,
            'collation' => 'utf8mb4_unicode_ci',
            'encoding' => 'utf8mb4',
            'comment' => '内容',
        ])->addColumn('author_user_id', 'integer', [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'signed' => false,
            'comment' => '作者用户id'
        ])->addColumn('created_at', 'datetime', [
            'null' => false,
            'default' => 'CURRENT_TIMESTAMP',
            'comment' => '创建时间',
        ])->addColumn('updated_at', 'datetime', [
            'null' => true,
            'comment' => '更新时间',
        ])->addIndex(['author_user_id'], [
            'name' => 'author',
            'unique' => false,
        ])->addForeignKey('author_user_id', 'user', 'id', [
            'delete'=> 'RESTRICT',
            'update'=> 'CASCADE',
        ])->create();
    }
}
