<?php

use yii\db\Migration;

/**
 * Class m240926_073726_init_business_images
 */
class m240926_073726_init_business_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $filePath = Yii::getAlias('@app/migrations/sql_files/m240926_073726_init_business_images.sql');
        $sql = file_get_contents($filePath);
        $this->execute("{$sql}");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%business_image}}');
    }
}
