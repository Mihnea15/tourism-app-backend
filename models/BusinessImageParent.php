<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business_image".
 *
 * @property int $id
 * @property int $business_id
 * @property string $image_name
 * @property string $image_path
 */
class BusinessImageParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_id', 'image_name', 'image_path'], 'required'],
            [['business_id'], 'integer'],
            [['image_name', 'image_path'], 'string', 'max' => 255],
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
            'image_name' => 'Image Name',
            'image_path' => 'Image Path',
        ];
    }
}
