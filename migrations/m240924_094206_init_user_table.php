<?php

use yii\db\Migration;

/**
 * Class m240924_094206_init_user_table
 */
class m240924_094206_init_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m240924_094206_init_user_table.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
