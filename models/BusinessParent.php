<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business".
 *
 * @property int $id
 * @property int|null $city_id
 * @property string|null $name
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $logo
 * @property string|null $google_page_url
 * @property float|null $rating
 * @property string|null $address
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $reviews
 * @property int|null $favourite
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
            [['id'], 'required'],
            [['id', 'city_id', 'favourite'], 'integer'],
            [['latitude', 'longitude', 'rating'], 'number'],
            [['address', 'reviews', 'description', 'program'], 'string'],
            [['name', 'logo', 'google_page_url', 'email', 'phone'], 'string', 'max' => 255],
            [['opening_hour', 'closing_hour'], 'string', 'max' => 5],
            [['id'], 'unique'],
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
            'rating' => 'Rating',
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
