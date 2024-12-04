<?php

use yii\db\Migration;

class m221106_101039_create_table_tbl_comments extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_comments}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'title_comment' => $this->text()->notNull(),
                'attach' => $this->string(72)->notNull(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_comments}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_comments}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_comments}}', ['creator_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_comments}}', ['slave_id']);
        $this->createIndex('task_id', '{{%tbl_comments}}', ['task_id', 'slave_id']);
        $this->createIndex('update_id', '{{%tbl_comments}}', ['update_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_comments_ibfk_1',
            '{{%tbl_comments}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_comments_ibfk_2',
            '{{%tbl_comments}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_comments_ibfk_3',
            '{{%tbl_comments}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_comments}}');
    }
}
