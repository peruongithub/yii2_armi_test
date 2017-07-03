<?php

use yii\db\Migration;

class m170630_145725_create_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(50)->notNull()->unique(),
            'ip' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'time_create' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'time_last_visit' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ], $tableOptions);

        $this->createTable('{{%session}}', [
            'id' => $this->string()->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer(11)->notNull(),
            'last_activity' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'PRIMARY KEY ([[id]])',
        ], $tableOptions);

        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer(11)->notNull(),
            'message' => $this->string(250),
            'writing' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');

        $this->dropTable('{{%session}}');

        $this->dropTable('{{%message}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170630_145725_create_tables cannot be reverted.\n";

        return false;
    }
    */
}
