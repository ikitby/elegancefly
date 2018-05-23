<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180523_073046_rating
 */
class m180523_073046_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ratings', [

            'id' => Schema::TYPE_PK,
            'progect_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rateuser_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rating' => Schema::TYPE_INTEGER . ' NOT NULL',
            'raiting_date' => Schema::TYPE_DATE . ' NOT NULL',

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180523_073046_rating cannot be reverted.\n";

        $this->dropTable('ratings');

    }

}
