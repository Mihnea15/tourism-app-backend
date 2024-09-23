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
 * @property string $google_page_url
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string|null $reviews
 * @property int $favourite
 * @property string|null $description
 * @property string|null $program
 * @property string|null $opening_hour
 * @property string|null $closing_hour
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
            [['city_id', 'name', 'latitude', 'longitude', 'logo', 'google_page_url', 'address', 'email', 'phone'], 'required'],
            [['city_id', 'favourite'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['description'], 'string'],
            [['opening_hour', 'closing_hour'], 'safe'],
            [['name', 'logo', 'google_page_url', 'address', 'email', 'phone', 'reviews', 'program'], 'string', 'max' => 255],
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
            'google_page_url' => 'Google Page Url',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
            'reviews' => 'Reviews',
            'favourite' => 'Favourite',
            'description' => 'Description',
            'program' => 'Program',
            'opening_hour' => 'Opening Hour',
            'closing_hour' => 'Closing Hour',
        ];
    }
}
