<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business".
 *
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property string $logo
 * @property string $google_page_link
 * @property string $address
 * @property string $email
 * @property string $phone
 */
class BusinessParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'name', 'latitude', 'longitude', 'logo', 'google_page_link', 'address', 'email', 'phone'], 'required'],
            [['city_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['name', 'logo', 'google_page_link', 'address', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'logo' => 'Logo',
            'google_page_link' => 'Google Page Link',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }
}
