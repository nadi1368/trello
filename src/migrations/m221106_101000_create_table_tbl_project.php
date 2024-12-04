<?php

use yii\db\Migration;

class m221106_101000_create_table_tbl_project extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_project}}',
            [
                'id' => $this->integer()->unsigned()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'user_id' => $this->integer()->unsigned(),
                'project_name' => $this->string()->notNull(),
                'project_status' => $this->integer()->unsigned()->notNull(),
                'status' => $this->integer()->unsigned()->notNull(),
                'public_or_private' => $this->integer()->notNull()->defaultValue('1'),
                'color' => $this->string(32),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_project}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_project}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('idx-project-creator_id', '{{%tbl_project}}', ['creator_id', 'slave_id']);
        $this->createIndex('idx-project-update_id', '{{%tbl_project}}', ['update_id', 'slave_id']);
        $this->createIndex('idx-project-user_id', '{{%tbl_project}}', ['user_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_project}}', ['slave_id']);

        $this->addForeignKey(
            'fk-project-creator_id',
            '{{%tbl_project}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project-update_id',
            '{{%tbl_project}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-project-user_id',
            '{{%tbl_project}}',
            ['user_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_project}}');
    }
}
