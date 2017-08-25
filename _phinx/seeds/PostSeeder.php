<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置文章。
 */
class PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());
        $prefixedTableName = $tableAdapter->getAdapterTableName('post');

        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `title` = '{{title}}';";

        $posts = [
            [
                'title'   => 'ThinkPHP 3.2',
                'content' => '国内流行的框架，简单易用，功能还不够全面，有些特性还不够完善。',
            ], [
                'title'   => 'CakePHP 2.x',
                'content' => '老牌的框架，文档详细，有中文翻译。',
            ], [
                'title'   => 'CakePHP 3.x',
                'content' => '改进了ORM，更加现代化。',
            ], [
                'title'   => 'Laravel',
                'content' => '最流行的PHP框架，完备的生态系统，极大地提高了生产力。',
            ], [
                'title'   => 'Yii',
                'content' => '中国人开发的框架，在Laravel出现前一度很流行。',
            ], [
                'title'   => 'Zend Framework',
                'content' => '老牌的框架，没用过，不了解。',
            ], [
                'title'   => 'Symfony',
                'content' => '老牌的框架，没用过，不了解。',
            ], [
                'title'   => 'CodeIgniter',
                'content' => '老牌的框架，没用过，不了解。',
            ], [
                'title'   => 'Phalcon',
                'content' => 'C写的框架，以PHP扩展的形式发布，速度快。没用过，不了解。',
            ]
        ];

        foreach ($posts as $post) {
            $sql = str_replace('{{title}}', $post['title'], $sqlTmpl);
            $row = $this->fetchRow($sql);
            if (empty($row)) {
                // $post['created_at'] = date('Y-m-d H:i:s.u');
                $data = [$post];
                $this->table('post')
                    ->insert($data)
                    ->saveData();
            }
        }
    }
}
