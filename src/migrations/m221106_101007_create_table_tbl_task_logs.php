<?php

use yii\db\Migration;

class m221106_101007_create_table_tbl_task_logs extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_task_logs}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'old_status' => $this->integer(),
                'new_status' => $this->integer(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_task_logs}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_task_logs}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_task_logs}}', ['creator_id', 'slave_id']);
        $this->createIndex('new_status', '{{%tbl_task_logs}}', ['new_status', 'slave_id']);
        $this->createIndex('old_status', '{{%tbl_task_logs}}', ['old_status', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_task_logs}}', ['slave_id']);
        $this->createIndex('task_id', '{{%tbl_task_logs}}', ['task_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_task_logs_ibfk_1',
            '{{%tbl_task_logs}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_task_logs_ibfk_2',
            '{{%tbl_task_logs}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_task_logs_ibfk_3',
            '{{%tbl_task_logs}}',
            ['new_status', 'slave_id'],
            '{{%tbl_project_status}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_task_logs_ibfk_4',
            '{{%tbl_task_logs}}',
            ['old_status', 'slave_id'],
            '{{%tbl_project_status}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_task_logs}}');
    }
}
