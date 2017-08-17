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
            'encoding' => 'utf8',
            'collation' => 'utf8_general_ci',
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
            'collation' => 'utf8_general_ci',
            'encoding' => 'utf8',
            'comment' => '标题',
        ])->addColumn('content', 'text', [
            'null' => false,
            'collation' => 'utf8_general_ci',
            'encoding' => 'utf8',
            'comment' => '内容',
        ])->addColumn('created_at', 'datetime', [
            'null' => false,
            'comment' => '创建时间',
        ])->addColumn('updated_at', 'datetime', [
            'null' => true,
            'comment' => '更新时间',
        ])->create();
    }
}
