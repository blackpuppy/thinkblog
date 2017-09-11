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
                'author'  => 'jane.lorna',
            ], [
                'title'   => 'Symfony',
                'content' => '老牌的框架，没用过，不了解。',
                'author'  => 'jane.lorna',
            ], [
                'title'   => 'CodeIgniter',
                'content' => '老牌的框架，没用过，不了解。',
                'author'  => 'jane.lorna',
            ], [
                'title'   => 'Phalcon',
                'content' => 'C写的框架，速度快。没用过，不了解。',
                'author'  => 'jane.lorna',
            ], [
                'title'   => 'JavaScript',
                'content' => '当今最热门的编程语言。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'Node.js',
                'content' => '服务器端的JavaScrip，在其上诞生了很多流行的框架，比如React，Vue.js。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'PHP',
                'content' => '使用人数最多的编程语言，简单易学。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'Java',
                'content' => '曾经最流行的跨平台编程语言。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'C#',
                'content' => '为.NET而生的编程语言，借鉴了Java的经验教训。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'C',
                'content' => '相比于面向应用的编程语言，这是更适合底层、对效率和性能要求较高的语言。',
                'author'  => 'dahl.ryan',
            ], [
                'title'   => 'HTML',
                'content' => '描述网页内容的标记语言。',
                'author'  => 'evan.you',
            ], [
                'title'   => 'CSS',
                'content' => '描述网页布局和视觉效果的语言。',
                'author'  => 'evan.you',
            ], [
                'title'   => 'React',
                'content' => 'Facebook开发的框架，简化了MVC的复杂性。',
                'author'  => 'evan.you',
            ], [
                'title'   => 'Vue.js',
                'content' => '比React更易于上手的框架，上升趋势明显快于React。',
                'author'  => 'evan.you',
            ], [
                'title'   => 'Vagrant',
                'content' => '虚拟机管理器，便于团队建立相同的开发环境。',
                'author'  => 'john.doe',
            ], [
                'title'   => 'Docker',
                'content' => '容器平台，虚拟化的进程管理，便于软件更好的发布。',
                'author'  => 'john.doe',
            ], [
                'title'   => 'DevOps',
                'content' => '简化流程，使开发人员和系统管理人员更好地合作，实现软件更流畅的开发、部署和监控。',
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
