<?php

namespace up;

use yii\db\Migration;

class m221106_101003_create_table_tbl_project_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_project_user}}',
            [
                'id' => $this->integer()->unsigned()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'user_id' => $this->integer()->unsigned()->notNull(),
                'project_id' => $this->integer()->unsigned()->notNull(),
                'role' => $this->integer()->notNull(),
                'is_creator' => $this->integer()->notNull()->defaultValue('0'),
                'status' => $this->integer()->unsigned()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_project_user}}', ['id', 'slave_id']);

        $this->alterColumn("{{%tbl_project_user}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('idx-project_user-creator_id', '{{%tbl_project_user}}', ['creator_id', 'slave_id']);
        $this->createIndex('idx-project_user-project_id', '{{%tbl_project_user}}', ['project_id', 'slave_id']);
        $this->createIndex('idx-project_user-update_id', '{{%tbl_project_user}}', ['update_id', 'slave_id']);
        $this->createIndex('idx-project_user-user_id', '{{%tbl_project_user}}', ['user_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_project_user}}', ['slave_id']);

        $this->addForeignKey(
            'fk-project_user-creator_id',
            '{{%tbl_project_user}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project_user-project_id',
            '{{%tbl_project_user}}',
            ['project_id', 'slave_id'],
            '{{%tbl_project}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project_user-update_id',
            '{{%tbl_project_user}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project_user-user_id',
            '{{%tbl_project_user}}',
            ['user_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_project_user}}');
    }
}
