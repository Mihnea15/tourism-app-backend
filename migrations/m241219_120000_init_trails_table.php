<?php

use yii\db\Migration;

/**
 * Class m241219_120000_init_trails_table
 */
class m241219_120000_init_trails_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m241219_120000_init_trails_table.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trails}}');
    }
} 