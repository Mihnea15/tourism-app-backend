<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favourite".
 *
 * @property int $id
 * @property int $business_id
 * @property int $user_id
 */
class FavouriteParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favourite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'user_id'], 'required'],
            [['business_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_id' => 'Business ID',
            'user_id' => 'User ID',
        ];
    }
}
