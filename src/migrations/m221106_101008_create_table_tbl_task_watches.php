<?php

use yii\db\Migration;

class m221106_101008_create_table_tbl_task_watches extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_task_watches}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'old_status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_task_watches}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_task_watches}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_task_watches}}', ['creator_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_task_watches}}', ['slave_id']);
        $this->createIndex('task_id', '{{%tbl_task_watches}}', ['task_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_task_watches_ibfk_1',
            '{{%tbl_task_watches}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_task_watches_ibfk_2',
            '{{%tbl_task_watches}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_task_watches}}');
    }
}
