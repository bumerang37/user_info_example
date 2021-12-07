<?php

use yii\db\Migration;

/**
 * Class m211206_205526_edit_auth_key_on_user_table
 */
class m211206_205526_edit_auth_key_on_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'auth_key', $this->string(32)->null()->comment('Ключ аутентификации cookies'));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->alterColumn('{{%user}}', 'auth_key', $this->string(32)->notNull()->comment('Ключ аутентификации cookies'));
        echo "m211206_205526_edit_auth_key_on_user_table cannot be reverted.\n";

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211206_205526_edit_auth_key_on_user_table cannot be reverted.\n";

        return false;
    }
    */
}
