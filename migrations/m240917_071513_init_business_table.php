<?php

use yii\db\Migration;

/**
 * Class m240917_071513_init_business_table
 */
class m240917_071513_init_business_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m240917_071513_init_business_table.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%business}}');
    }
}
