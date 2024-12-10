<?php

namespace up;

use yii\db\Migration;

class m221106_101004_create_table_tbl_task_assignment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_task_assignment}}',
            [
                'id' => $this->integer()->unsigned()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'user_id' => $this->integer()->unsigned()->notNull(),
                'project_id' => $this->integer()->unsigned()->notNull(),
                'parent_id' => $this->integer()->unsigned(),
                'status' => $this->integer()->unsigned()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_task_assignment}}', ['id', 'slave_id']);

        $this->alterColumn("{{%tbl_task_assignment}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('idx-task_assignment-creator_id', '{{%tbl_task_assignment}}', ['creator_id', 'slave_id']);
        $this->createIndex('idx-task_assignment-project_id', '{{%tbl_task_assignment}}', ['project_id', 'slave_id']);
        $this->createIndex('idx-task_assignment-task_id', '{{%tbl_task_assignment}}', ['task_id', 'slave_id']);
        $this->createIndex('idx-task_assignment-update_id', '{{%tbl_task_assignment}}', ['update_id', 'slave_id']);
        $this->createIndex('idx-task_assignment-user_id', '{{%tbl_task_assignment}}', ['user_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_task_assignment}}', ['slave_id']);

        $this->addForeignKey(
            'fk-task_assignment-creator_id',
            '{{%tbl_task_assignment}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-task_assignment-project_id',
            '{{%tbl_task_assignment}}',
            ['project_id', 'slave_id'],
            '{{%tbl_project}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-task_assignment-task_id',
            '{{%tbl_task_assignment}}',
            ['task_id', 'slave_id'],
            '{{%tbl_project_task}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-task_assignment-update_id',
            '{{%tbl_task_assignment}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-task_assignment-user_id',
            '{{%tbl_task_assignment}}',
            ['user_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_task_assignment}}');
    }
}
