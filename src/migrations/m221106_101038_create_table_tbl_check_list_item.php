<?php

use yii\db\Migration;

class m221106_101038_create_table_tbl_check_list_item extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_check_list_item}}',
            [
                'id' => $this->integer()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'check_list_id' => $this->integer()->notNull(),
                'title_item' => $this->string(48)->notNull(),
                'status' => $this->integer()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_check_list_item}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_check_list_item}}", 'id', $this->integer()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('check_list_id', '{{%tbl_check_list_item}}', ['check_list_id', 'slave_id']);
        $this->createIndex('creator_id', '{{%tbl_check_list_item}}', ['creator_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_check_list_item}}', ['slave_id']);
        $this->createIndex('update_id', '{{%tbl_check_list_item}}', ['update_id', 'slave_id']);

        $this->addForeignKey(
            'tbl_check_list_item_ibfk_1',
            '{{%tbl_check_list_item}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_check_list_item_ibfk_2',
            '{{%tbl_check_list_item}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'tbl_check_list_item_ibfk_3',
            '{{%tbl_check_list_item}}',
            ['check_list_id', 'slave_id'],
            '{{%tbl_check_list}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_check_list_item}}');
    }
}
