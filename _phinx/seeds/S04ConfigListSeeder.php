<?php

use Phinx\Seed\AbstractSeed;
use Phinx\Db\Adapter\TablePrefixAdapter;

/**
 * 预置文章。
 */
class S04ConfigListSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $tableAdapter = new TablePrefixAdapter($this->getAdapter());

        $prefixedTableName = $tableAdapter->getAdapterTableName('config_list');
        $sqlTmpl = "SELECT * FROM `$prefixedTableName` WHERE `list_name` = '{{list_name}}' AND `list_key` = '{{list_key}}';";

        $configs = [
            [
                'list_name'         => 'gender',
                'list_key'          => 'male',
                'list_value'        => 'male',
                'list_value_desc'   => 'Male',
                'display_order'     => 1,
            ], [
                'list_name'         => 'gender',
                'list_key'          => 'female',
                'list_value'        => 'female',
                'list_value_desc'   => 'Female',
                'display_order'     => 2,
            ]
        ];

        foreach ($configs as $config) {
            $sql = str_replace('{{list_name}}', $config['list_name'], $sqlTmpl);
            $sql = str_replace('{{list_key}}', $config['list_key'], $sqlTmpl);
            $row = $this->fetchRow($sql);
            if (empty($row)) {
                $config['created_by'] = 1;
                $this->table('config_list')
                    ->insert($config)
                    ->saveData();
            }
        }
    }
}
