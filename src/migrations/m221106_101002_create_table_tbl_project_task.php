<?php

use yii\db\Migration;

class m221106_101002_create_table_tbl_project_task extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_project_task}}',
            [
                'id' => $this->integer()->unsigned()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'start' => $this->integer()->notNull(),
                'end' => $this->integer()->notNull(),
                'title_task' => $this->string(256),
                'desc_task' => $this->text(),
                't_order' => $this->integer()->notNull()->defaultValue('1'),
                'list_id' => $this->integer()->notNull(),
                'status' => $this->integer()->notNull()->defaultValue('1'),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_project_task}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_project_task}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('idx-project_task-creator_id', '{{%tbl_project_task}}', ['creator_id', 'slave_id']);
        $this->createIndex('idx-project_task-update_id', '{{%tbl_project_task}}', ['update_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_project_task}}', ['slave_id']);
        $this->createIndex('status', '{{%tbl_project_task}}', ['list_id', 'slave_id']);

        $this->addForeignKey(
            'fk-project_task-creator_id',
            '{{%tbl_project_task}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project_task-update_id',
            '{{%tbl_project_task}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_project_task_ibfk_1',
            '{{%tbl_project_task}}',
            ['list_id', 'slave_id'],
            '{{%tbl_project_status}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_project_task}}');
    }
}
