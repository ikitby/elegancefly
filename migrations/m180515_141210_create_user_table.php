<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180515_141210_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'usertype' => $this->string(250)->notNull(),
            'photo' => $this->string(250),
            'birthday' => $this->string(80),
            'created_at' => $this->string(80),
            'country' => $this->string(80),
            'languages' => $this->string(250),
            'fbpage' => $this->string(250),
            'vkpage' => $this->string(250),
            'inpage' => $this->string(250),
            'percent' => $this->string(3),
            'state' => $this->string(10),
            'role' => $this->string(30),
            'rate' => $this->string(10),
            'balance' => $this->string(10),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}


