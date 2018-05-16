<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180516_131217_linctables
 */
class m180516_131217_linctables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('project_thems', [
            'id' => Schema::TYPE_PK,
            'progect_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'theme_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->createTable('project_tags', [
            'id' => Schema::TYPE_PK,
            'progect_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180516_131217_linctables cannot be reverted.\n";

        $this->dropTable('project_thems');
        $this->dropTable('project_tags');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_131217_linctables cannot be reverted.\n";

        return false;
    }
    */
}
