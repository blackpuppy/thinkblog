<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置用户。
 */
class S02UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());

        $prefixedTableName = $tableAdapter->getAdapterTableName('auth_group');
        $sql = "SELECT `id`, `title` FROM `$prefixedTableName` WHERE `status` = 1;";
        $allGroups = $this->fetchAll($sql);
        $groupMap = [];
        array_walk($allGroups, function ($group) use (&$groupMap)
        {
           $groupMap[$group['title']] = $group['id'];
        });

        $prefixedTableName = $tableAdapter->getAdapterTableName('user');
        $selectUserTmpl = "SELECT `id` FROM `$prefixedTableName` WHERE `name` = '{{name}}';";
        $updateUserTmpl = "UPDATE `$prefixedTableName` SET `password` = '{{password}}', `first_name` = '{{first_name}}', `last_name` = '{{last_name}}', `email` = '{{email}}', `updated_by` = {{updated_by}}, `updated_at` = '{{updated_at}}' WHERE `name` = '{{name}}';";

        $prefixedTableName = $tableAdapter->getAdapterTableName('user_group');
        $deleteUserGroupTmpl = "DELETE FROM `$prefixedTableName` WHERE `uid` = {{uid}};";

        $users = [
            [
                'name'          => 'admin',
                'password'      => 'admin',
                'first_name'    => 'Administraor',
                'email'         => 'admin@example.com',
                'groups'        => ['管理员'],
            ], [
                'name'          => 'tayler.otwell',
                'password'      => 'P@55w0rd',
                'first_name'    => 'Tayler',
                'last_name'     => 'Otwell',
                'email'         => 'tayler.otwell@example.com',
                'groups'        => ['作者'],
            ], [
                'name'          => 'mark.story',
                'password'      => 'P@55w0rd',
                'first_name'    => 'Mark',
                'last_name'     => 'Story',
                'email'         => 'mark.story@example.com',
                'groups'        => ['作者'],
            ], [
                'name'          => 'qiang.xue',
                'password'      => 'P@55w0rd',
                'first_name'    => '强',
                'last_name'     => '薛',
                'email'         => 'qiang.xue@example.com',
                'groups'        => ['作者'],
            ], [
                'name'          => 'chen.liu',
                'password'      => 'P@55w0rd',
                'first_name'    => '晨',
                'last_name'     => '刘',
                'email'         => 'chen.liu@example.com',
                'groups'        => ['作者'],
            ], [
                'name'          => 'jane.lorna',
                'password'      => 'P@55w0rd',
                'first_name'    => 'Jane',
                'last_name'     => 'Lorna',
                'email'         => 'jane.lorna@example.com',
                'groups'        => ['作者'],
            ], [
                'name'          => 'john.doe',
                'password'      => 'P@55w0rd',
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'email'         => 'john.doe@example.com',
                'groups'        => ['作者'],
            ]
        ];

        foreach ($users as $user) {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

            $groups = $user['groups'];
            unset($user['groups']);

            if (count($groupMap) > 0) {
                array_walk(
                    $groups,
                    function (&$group, $ndx, $groupMap)
                    {
                       $group = $groupMap[$group];
                    },
                    $groupMap
                );
            }

            $sql = str_replace('{{name}}', $user['name'], $selectUserTmpl);
            $userRow = $this->fetchRow($sql);
            if (empty($userRow)) {
                $user['created_by'] = 1;
                $this->table('user')
                    ->insert($user)
                    ->saveData();

                // 读取插入的记录
                $sql = str_replace('{{name}}', $user['name'], $selectUserTmpl);
                $userRow = $this->fetchRow($sql);
            } else {
                $user['updated_by'] = 1;
                $user['updated_at'] = date('Y-m-d H:i:s.u');

                $sql = str_replace('{{name}}', $user['name'], $updateUserTmpl);
                $sql = str_replace('{{password}}', $user['password'], $sql);
                $sql = str_replace('{{first_name}}', $user['first_name'], $sql);
                $sql = str_replace('{{last_name}}', $user['last_name'], $sql);
                $sql = str_replace('{{email}}', $user['email'], $sql);
                $sql = str_replace('{{updated_by}}', $user['updated_by'], $sql);
                $sql = str_replace('{{updated_at}}', $user['updated_at'], $sql);
                $count = $this->execute($sql);
            }

            $user['id'] = $userRow['id'];

            if (count($groupMap) > 0) {
                $sql = str_replace('{{uid}}', $user['id'], $deleteUserGroupTmpl);
                $count = $this->execute($sql);

                foreach ($groups as $group_id) {
                    $userGroup = [
                        'uid' => $user['id'],
                        'group_id' => $group_id,
                        'created_by' => 1,
                    ];
                    $this->table('user_group')
                        ->insert($userGroup)
                        ->saveData();
                }
            }
        }
    }
}
