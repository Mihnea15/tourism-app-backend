<?php

use yii\db\Migration;

/**
 * Class m240905_070846_init_tourism_app_db
 */
class m240905_070846_init_tourism_app_db extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('CREATE DATABASE IF NOT EXISTS tourism_app')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP DATABASE IF EXISTS tourism_app')->execute();
    }
}
