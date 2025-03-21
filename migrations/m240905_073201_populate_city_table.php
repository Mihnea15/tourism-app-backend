<?php

use yii\db\Migration;

/**
 * Class m240905_073201_populate_city_table
 */
class m240905_073201_populate_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m240905_073201_populate_city_table.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
