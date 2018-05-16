<?php

use yii\db\Migration;

/**
 * Class m180516_055203_user
 */
class m180516_055203_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function Up()
    {
            $this->addColumn('user', 'usertype', $this->string(250)->notNull());
            $this->addColumn('user', 'photo', $this->string(250));
            $this->addColumn('user', 'birthday', $this->string(80));
            $this->addColumn('user', 'country', $this->string(80));
            $this->addColumn('user', 'languages', $this->string(250));
            $this->addColumn('user', 'fbpage', $this->string(250));
            $this->addColumn('user', 'vkpage', $this->string(250));
            $this->addColumn('user', 'inpage', $this->string(250));
            $this->addColumn('user', 'percent', $this->string(3));
            $this->addColumn('user', 'state', $this->string(10));
            $this->addColumn('user', 'role', $this->string(30));
            $this->addColumn('user', 'rate', $this->string(10));
            $this->addColumn('user', 'balance', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180516_055203_user cannot be reverted.\n";

        $this->dropColumn('user', 'usertype');
        $this->dropColumn('user', 'photo');
        $this->dropColumn('user', 'birthday');
        $this->dropColumn('user', 'country');
        $this->dropColumn('user', 'languages');
        $this->dropColumn('user', 'fbpage');
        $this->dropColumn('user', 'vkpage');
        $this->dropColumn('user', 'inpage');
        $this->dropColumn('user', 'percent');
        $this->dropColumn('user', 'state');
        $this->dropColumn('user', 'role');
        $this->dropColumn('user', 'rate');
        $this->dropColumn('user', 'balance');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_055203_user cannot be reverted.\n";

        return false;
    }
    */
}
