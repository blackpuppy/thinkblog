<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

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
                'title'   => 'First post',
                'content' => 'This is first post.',
            ], [
                'title'   => 'Second post',
                'content' => 'This is second post.',
            ], [
                'title'   => 'Third post',
                'content' => 'This is third post.',
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
