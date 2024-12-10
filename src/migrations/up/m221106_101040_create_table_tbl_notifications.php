<?php

namespace up;

use yii\db\Migration;

class m221106_101040_create_table_tbl_notifications extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_notifications}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'project_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'title_not' => $this->string(48)->notNull(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_notifications}}', ['id', 'slave_id']);

        $this->alterColumn("{{%tbl_notifications}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('creator_id', '{{%tbl_notifications}}', ['creator_id', 'slave_id']);
        $this->createIndex('project_id', '{{%tbl_notifications}}', ['project_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_notifications}}', ['slave_id']);
        $this->createIndex('task_id', '{{%tbl_notifications}}', ['task_id', 'slave_id']);
        $this->createIndex('update_id', '{{%tbl_notifications}}', ['update_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_notifications_ibfk_1',
            '{{%tbl_notifications}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_notifications_ibfk_2',
            '{{%tbl_notifications}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_notifications_ibfk_3',
            '{{%tbl_notifications}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_notifications_ibfk_4',
            '{{%tbl_notifications}}',
            ['project_id', 'slave_id'],
            '{{%tbl_project}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_notifications}}');
    }
}
