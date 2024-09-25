<?php

use yii\db\Migration;

/**
 * Class m240925_104200_init_favourite_table
 */
class m240925_104200_init_favourite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m240925_104200_init_favourite_table.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%favourite}}');
    }
}
