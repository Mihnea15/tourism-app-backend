<?php

namespace app\models;

use yii\db\ActiveRecord;

class TrailsParent extends ActiveRecord
{
    public static function tableName()
    {
        return 'trails';
    }

    public function rules()
    {
        return [
            [['name', 'difficulty', 'start_point_lat', 'start_point_lng', 'end_point_lat', 'end_point_lng', 'coordinates'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['difficulty'], 'in', 'range' => ['easy', 'medium', 'hard']],
            [['length_km'], 'number', 'max' => 9999999999.99],
            [['elevation_gain', 'estimated_time', 'city_id'], 'integer'],
            [['start_point_lat', 'start_point_lng', 'end_point_lat', 'end_point_lng'], 'number'],
            [['coordinates', 'markers'], 'safe'],
            [['region'], 'string', 'max' => 100],
            [['trail_type'], 'in', 'range' => ['hiking', 'biking', 'mixed']],
            [['season'], 'in', 'range' => ['summer', 'winter', 'all']],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'difficulty' => 'Difficulty',
            'length_km' => 'Length (km)',
            'elevation_gain' => 'Elevation Gain',
            'estimated_time' => 'Estimated Time',
            'start_point_lat' => 'Start Point Latitude',
            'start_point_lng' => 'Start Point Longitude',
            'end_point_lat' => 'End Point Latitude',
            'end_point_lng' => 'End Point Longitude',
            'coordinates' => 'Coordinates',
            'city_id' => 'City ID',
            'region' => 'Region',
            'trail_type' => 'Trail Type',
            'season' => 'Season',
            'markers' => 'Markers',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
