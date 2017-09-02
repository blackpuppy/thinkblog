<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置文章。
 */
class S03PostSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());

        $prefixedTableName = $tableAdapter->getAdapterTableName('user');
        $sql = "SELECT `id`, `name` FROM `$prefixedTableName`;";
        $allUsers = $this->fetchAll($sql);
        $userMap = [];
        array_walk($allUsers, function ($user) use (&$userMap)
        {
           $userMap[$user['name']] = $user['id'];
        });

        // $this->getOutput()->writeln(
        //     PHP_EOL . '$allUsers = ' . print_r($allUsers, true)
        //     . PHP_EOL . '$userMap = ' . print_r($userMap, true)
        // );

        $prefixedTableName = $tableAdapter->getAdapterTableName('post');
        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `title` = '{{title}}';";

        $posts = [
            [
                'title'   => 'CakePHP 2.x',
                'content' => '老牌的框架，文档详细，有中文翻译。',
                'author'  => 'mark.story',
            ], [
                'title'   => 'CakePHP 3.x',
                'content' => 'CakePHP的当前版本。改进了ORM，更加现代化。',
                'author'  => 'mark.story',
            ], [
                'title'   => 'Laravel',
                'content' => '最流行的PHP框架，完备的生态系统，极大地提高了生产力。',
                'author'  => 'tayler.otwell',
            ], [
                'title'   => 'Yii',
                'content' => '中国人开发的框架，在Laravel出现前一度很流行。',
                'author'  => 'qiang.xue',
            ], [
                'title'   => 'ThinkPHP 3.2',
                'content' => '国内流行的框架，简单易用，功能还不够全面，有些特性还不够完善。',
                'author'  => 'chen.liu',
            ], [
                'title'   => 'ThinkPHP 5',
                'content' => 'ThinkPHP的当前版本。没用过，不了解。',
                'author'  => 'chen.liu',
            ], [
                'title'   => 'Zend Framework',
                'content' => '老牌的框架，没用过，不了解。',
                'author'  => 'john.doe',
            ], [
                'title'   => 'Symfony',
                'content' => '老牌的框架，没用过，不了解。',
                'author'  => 'john.doe',
            ], [
                'title'   => 'CodeIgniter',
                'content' => '老牌的框架，没用过，不了解。',
                'author'  => 'john.doe',
            ], [
                'title'   => 'Phalcon',
                'content' => 'C写的框架，速度快。没用过，不了解。',
                'author'  => 'john.doe',
            ]
        ];

        foreach ($posts as $post) {
            if (count($userMap) > 0) {
                $post['author_user_id'] = $userMap[$post['author']];
            }

            // $this->getOutput()->writeln(
            //     PHP_EOL . '$post = ' . print_r($post, true)
            // );

            unset($post['author']);

            if (isset($post['author_user_id'])) {
                $sql = str_replace('{{title}}', $post['title'], $sqlTmpl);
                $row = $this->fetchRow($sql);
                if (empty($row)) {
                    $post['created_by'] = 1;
                    $this->table('post')
                        ->insert($post)
                        ->saveData();
                }
            }
        }
    }
}
