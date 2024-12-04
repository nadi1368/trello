<?php

use yii\db\Migration;

class m221106_101010_create_table_tbl_team_users extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_team_users}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'team_id' => $this->integer()->notNull(),
                'user_id' => $this->integer()->unsigned()->notNull(),
                'role' => $this->integer()->notNull(),
                'is_creator' => $this->integer()->notNull()->defaultValue('0'),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_team_users}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_team_users}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_team_users}}', ['creator_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_team_users}}', ['slave_id']);
        $this->createIndex('team_id', '{{%tbl_team_users}}', ['team_id', 'slave_id']);
        $this->createIndex('update_id', '{{%tbl_team_users}}', ['update_id', 'slave_id']);
        $this->createIndex('user_id', '{{%tbl_team_users}}', ['user_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_team_users_ibfk_1',
            '{{%tbl_team_users}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_team_users_ibfk_2',
            '{{%tbl_team_users}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_team_users_ibfk_3',
            '{{%tbl_team_users}}',
            ['team_id', 'slave_id'],
            '{{%tbl_team}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_team_users_ibfk_4',
            '{{%tbl_team_users}}',
            ['user_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_team_users}}');
    }
}
