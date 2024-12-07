<?php

use yii\db\Migration;

class m221106_101036_create_table_tbl_attachments extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_attachments}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'attach' => $this->string(48)->notNull(),
                'base_name' => $this->string(128)->notNull(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_attachments}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_attachments}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_attachments}}', ['creator_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_attachments}}', ['slave_id']);
        $this->createIndex('task_id', '{{%tbl_attachments}}', ['task_id', 'slave_id']);
        $this->createIndex('update_id', '{{%tbl_attachments}}', ['update_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_attachments_ibfk_1',
            '{{%tbl_attachments}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_attachments_ibfk_2',
            '{{%tbl_attachments}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_attachments_ibfk_3',
            '{{%tbl_attachments}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_attachments}}');
    }
}
