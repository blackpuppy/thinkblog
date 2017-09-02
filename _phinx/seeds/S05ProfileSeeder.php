<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置文章。
 */
class S05ProfileSeeder extends AbstractSeed
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

        $prefixedTableName = $tableAdapter->getAdapterTableName('profile');
        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `user_id` = '{{user_id}}';";

        $faker = Faker\Factory::create();

        $profiles = [
            [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'admin',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'mark.story',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'tayler.otwell',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'qiang.xue',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'chen.liu',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'female',
                'username'    => 'jane.lorna',
            ], [
                'address'     => str_replace(PHP_EOL, ' ', $faker->address),
                'postal_code' => $faker->postcode,
                'gender_key'  => 'male',
                'username'    => 'john.doe',
            ]
        ];

        foreach ($profiles as $profile) {
            if (count($userMap) > 0) {
                $profile['user_id'] = $userMap[$profile['username']];
            }

            unset($profile['username']);

            if (isset($profile['user_id'])) {
                $sql = str_replace('{{user_id}}', $profile['user_id'], $sqlTmpl);
                $row = $this->fetchRow($sql);
                if (empty($row)) {
                    $profile['created_by'] = $profile['user_id'];
                    $this->table('profile')
                        ->insert($profile)
                        ->saveData();
                }
            }
        }
    }
}
