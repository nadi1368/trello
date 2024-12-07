<?php

use yii\db\Migration;

class m221106_101041_create_table_tbl_project_teams extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_project_teams}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'project_id' => $this->integer()->unsigned()->notNull(),
                'team_id' => $this->integer()->notNull(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_project_teams}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_project_teams}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_project_teams}}', ['creator_id', 'slave_id']);
        $this->createIndex('project_id', '{{%tbl_project_teams}}', ['project_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_project_teams}}', ['slave_id']);
        $this->createIndex('team_id', '{{%tbl_project_teams}}', ['team_id', 'slave_id']);
        $this->createIndex('update_id', '{{%tbl_project_teams}}', ['update_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_project_teams_ibfk_1',
            '{{%tbl_project_teams}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_project_teams_ibfk_2',
            '{{%tbl_project_teams}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_project_teams_ibfk_3',
            '{{%tbl_project_teams}}',
            ['project_id', 'slave_id'],
            '{{%tbl_project}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_project_teams_ibfk_4',
            '{{%tbl_project_teams}}',
            ['team_id', 'slave_id'],
            '{{%tbl_team}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_project_teams}}');
    }
}
