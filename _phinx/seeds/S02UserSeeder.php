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

        $prefixedTableName = $tableAdapter->getAdapterTableName('group');
        $sql = "SELECT `id`, `title` FROM `$prefixedTableName` WHERE `status` = 1;";
        $allGroups = $this->fetchAll($sql);
        $groupMap = [];
        array_walk($allGroups, function ($group) use (&$groupMap)
        {
           $groupMap[$group['title']] = $group['id'];
        });

        $prefixedTableName = $tableAdapter->getAdapterTableName('user');
        $selectUserTmpl = "SELECT `id` FROM `$prefixedTableName` WHERE `name` = '{{name}}';";
        $updateUserTmpl = "UPDATE `$prefixedTableName` SET `password` = '{{password}}', `first_name` = '{{first_name}}', `last_name` = '{{last_name}}', `email` = '{{email}}', `updated_at` = '{{updated_at}}' WHERE `name` = '{{name}}';";

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
                'name'          => 'twain.mark',
                'password'      => 'P@55w0rd',
                'first_name'    => 'Mark',
                'last_name'     => 'Twain',
                'email'         => 'twain.mark@example.com',
                'groups'        => ['作者'],
            ]
        ];

        foreach ($users as $user) {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);

            $groups = $user['groups'];
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
            unset($user['groups']);

            $sql = str_replace('{{name}}', $user['name'], $selectUserTmpl);
            $userRow = $this->fetchRow($sql);
            if (empty($userRow)) {
                $this->table('user')
                    ->insert($user)
                    ->saveData();

                // 读取插入的记录
                $sql = str_replace('{{name}}', $user['name'], $selectUserTmpl);
                $userRow = $this->fetchRow($sql);
            } else {
                $user['updated_at'] = date('Y-m-d H:i:s.u');

                $sql = str_replace('{{name}}', $user['name'], $updateUserTmpl);
                $sql = str_replace('{{password}}', $user['password'], $sql);
                $sql = str_replace('{{first_name}}', $user['first_name'], $sql);
                $sql = str_replace('{{last_name}}', $user['last_name'], $sql);
                $sql = str_replace('{{email}}', $user['email'], $sql);
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
                    ];
                    $this->table('user_group')
                        ->insert($userGroup)
                        ->saveData();
                }
            }
        }
    }
}
