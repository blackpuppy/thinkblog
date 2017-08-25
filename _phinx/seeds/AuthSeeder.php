<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置用户授权规则。
 */
class AuthSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $this->SeedAuthRule();
        $this->SeedGroup();
    }

    protected function SeedAuthRule()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());
        $prefixedTableName = $tableAdapter->getAdapterTableName('auth_rule');

        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `name` = '{{name}}';";

        $rules = [
            [
            // 可以公开访问的页面
            //     'name'      => 'User/signup',
            //     'title'     => '注册新用户',
            //     'type'      => 1,
            //     'status'    => 1,
            //     'condition' => '',
            // ], [
            //     'name'      => 'User/login',
            //     'title'     => '用户登录',
            //     'type'      => 1,
            //     'status'    => 1,
            //     'condition' => '',
            // ], [
            //     'name'      => 'Post/index',
            //     'title'     => '文章列表',
            //     'type'      => 1,
            //     'status'    => 1,
            //     'condition' => '',
            // ], [
            //     'name'      => 'Post/view',
            //     'title'     => '阅读文章',
            //     'type'      => 1,
            //     'status'    => 1,
            //     'condition' => '',
            // ], [

            // 需要通过用户验证的页面
                'name'      => 'User/logout',
                'title'     => '注销用户',
                'type'      => 1,
                'status'    => 1,
                'condition' => '',
            ], [
                'name'      => 'Post/create',
                'title'     => '添加文章',
                'type'      => 1,
                'status'    => 1,
                'condition' => '',
            ], [
                'name'      => 'Post/update',
                'title'     => '修改文章',
                'type'      => 1,
                'status'    => 1,
                'condition' => '',
            ], [
                'name'      => 'Post/delete',
                'title'     => '删除文章',
                'type'      => 1,
                'status'    => 1,
                'condition' => '',
            ]
        ];

        foreach ($rules as $rule) {
            $sql = str_replace('{{name}}', $rule['name'], $sqlTmpl);
            $row = $this->fetchRow($sql);
            if (empty($row)) {
                $data = [$rule];
                $this->table('auth_rule')
                    ->insert($data)
                    ->saveData();
            }
        }
    }

    protected function SeedGroup()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());

        $prefixedTableName = $tableAdapter->getAdapterTableName('auth_rule');
        $sql = "SELECT * FROM `$prefixedTableName` WHERE `status` = 1;";
        $allRules = $this->fetchAll($sql);
        $ruleMap = [];
        array_walk(
            $allRules,
            function ($rule, $ndx) use (&$ruleMap)
            {
               $ruleMap[$rule['name']] = $rule['id'];
            },
            $ruleMap
        );

        $prefixedTableName = $tableAdapter->getAdapterTableName('group');
        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `title` = '{{title}}';";
        $updateTmpl = "UPDATE `$prefixedTableName` SET `rules` = '{{rules}}', `updated_at` = '{{updated_at}}' WHERE `title` = '{{title}}';";

        $groups = [
            [
                'title'     => '管理员',
                'status'    => 1,
                'rules'     => [
                    'User/logout',
                    'Post/create',
                    'Post/update',
                    'Post/delete',
                ],
            ], [
                'title'     => '作者',
                'status'    => 1,
                'rules'     => [
                    'User/logout',
                    'Post/create',
                    'Post/update',
                    'Post/delete',
                ],
            // 以后增加
            // ], [
            //     'title'     => '读者',
            //     'status'    => 1,
            //     'rules'     => [
            //         'Comment/create',
            //         'Comment/update',
            //         'Comment/delete',
            //     ],
            ]
        ];

        foreach ($groups as $group) {
            $rules = $group['rules'];
            array_walk(
                $rules,
                function (&$rule, $ndx, $ruleMap)
                {
                   $rule = (string) $ruleMap[$rule];
                },
                $ruleMap
            );
            $group['rules'] = implode(',', $rules);

            $sql = str_replace('{{title}}', $group['title'], $sqlTmpl);
            $row = $this->fetchRow($sql);

            if (empty($row)) {
                $data = [$group];
                $this->table('group')
                    ->insert($data)
                    ->saveData();
            } else {
                $group['updated_at'] = date('Y-m-d H:i:s.u');
                $sql = str_replace('{{title}}', $group['title'], $updateTmpl);
                $sql = str_replace('{{rules}}', $group['rules'], $sql);
                $sql = str_replace('{{updated_at}}', $group['updated_at'], $sql);
                $count = $this->execute($sql);
            }
        }
    }
}
