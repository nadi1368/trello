<?php

use yii\db\Migration;

/**
 * Class m241204_062040_alter_tbl_check_list_item_table
 */
class m241204_062040_alter_tbl_check_list_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tbl_check_list_item', 'title_item', $this->string(255));

        return True;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('tbl_check_list_item', 'title_item', $this->string(48));

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241204_062040_alter_tbl_check_list_item_table cannot be reverted.\n";

        return false;
    }
    */
}
