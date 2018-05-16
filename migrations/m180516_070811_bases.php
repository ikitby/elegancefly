<?php

use yii\db\Migration;
use yii\db\Schema;


/**
 * Class m180516_070811_bases
 */
class m180516_070811_bases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('products', [
            'id' => Schema::TYPE_PK,
            'user_ad' => Schema::TYPE_INTEGER,
            'Архив для загрузки' => Schema::TYPE_STRING,
            'tags' => Schema::TYPE_STRING,
            'photos' => Schema::TYPE_STRING . ' NOT NULL',
            'price' => Schema::TYPE_INTEGER . ' NOT NULL',
            'themes' => Schema::TYPE_STRING . ' NOT NULL',
            'limit' => Schema::TYPE_INTEGER,
            'hits' => Schema::TYPE_INTEGER,
            'sales' => Schema::TYPE_INTEGER,
        ]);

        $this->createTable('transaction', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'recipient_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'amount' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);


        $this->createTable('tags', [
            'id' => Schema::TYPE_PK,
	        'title' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createTable('themsprod', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createTable('catprod', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createTable('catblog', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createTable('article', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'reting' => Schema::TYPE_INTEGER,
            'image' => Schema::TYPE_STRING,
            'text' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_DATETIME . ' NOT NULL',
            'hits' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            'category_id' => Schema::TYPE_INTEGER,
            'comments' => Schema::TYPE_INTEGER,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180516_070811_bases cannot be reverted.\n";

        $this->dropTable('products');
        $this->dropTable('transaction');
        $this->dropTable('tags');
        $this->dropTable('themsprod');
        $this->dropTable('catprod');
        $this->dropTable('catblog');
        $this->dropTable('article');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_070811_bases cannot be reverted.\n";

        return false;
    }
    */
}
