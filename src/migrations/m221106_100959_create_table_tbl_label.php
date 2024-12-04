<?php

use yii\db\Migration;

class m221106_100959_create_table_tbl_label extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tbl_label}}',
            [
                'id' => $this->integer()->unsigned()->notNull(),
                'creator_id' => $this->integer()->unsigned()->notNull(),
                'update_id' => $this->integer()->unsigned()->notNull(),
                'label_name' => $this->string()->notNull(),
                'color_code' => $this->string(10),
                'status' => $this->integer()->unsigned()->notNull(),
                'created' => $this->integer()->unsigned()->notNull(),
                'changed' => $this->integer()->unsigned()->notNull(),
                'slave_id' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%tbl_label}}', ['id', 'slave_id']);

		$this->alterColumn("{{%tbl_label}}", 'id', $this->integer()->unsigned()->notNull()->append('AUTO_INCREMENT'));

        $this->createIndex('idx-label-creator_id', '{{%tbl_label}}', ['creator_id', 'slave_id']);
        $this->createIndex('idx-label-update_id', '{{%tbl_label}}', ['update_id', 'slave_id']);
        $this->createIndex('slave_id_index', '{{%tbl_label}}', ['slave_id']);

        $this->addForeignKey(
            'fk-label-creator_id',
            '{{%tbl_label}}',
            ['creator_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk-label-update_id',
            '{{%tbl_label}}',
            ['update_id', 'slave_id'],
            '{{%user}}',
            ['id', 'slave_id'],
            'NO ACTION',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tbl_label}}');
    }
}
