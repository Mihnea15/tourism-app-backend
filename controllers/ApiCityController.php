<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use app\models\Trails;
use yii\rest\Controller;
use yii\web\Response;
use GuzzleHttp\Client;

class ApiCityController extends Controller
{
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    public function actionIndex($latitude, $longitude)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        header('Access-Control-Allow-Origin: *');
        
        $cities = City::find()->asArray()->orderBy(['id' => SORT_ASC])->all();
        $businesses = Business::find()->asArray()->all();
        $trails = Trails::find()->asArray()->all();
        
        $closestCity = $this->findClosestCity($cities, $latitude, $longitude);
        
        $localBusinesses = $this->filterByCity($businesses, $closestCity['id'] ?? null);
        $localTrails = $this->filterByCity($trails, $closestCity['id'] ?? null);
        
        if (empty($localBusinesses) && empty($localTrails)) {
            $businesses = $this->generateAIAttractions($latitude, $longitude, $closestCity['name'] ?? null);
        }
        
        if (empty($localTrails)) {
            $trails = $this->generateMountainTrails($latitude, $longitude, 'Vatra Dornei');
        }
        
        return [
            'cities' => $cities,
            'business' => $businesses,
            'trails' => $trails
        ];
    }
    
    /**
     * Find the closest city to the given coordinates
     * 
     * @param array $cities List of cities
     * @param float $latitude User latitude
     * @param float $longitude User longitude
     * @return array|null The closest city or null if no cities
     */
    private function findClosestCity($cities, $latitude, $longitude)
    {
        if (empty($cities)) {
            return null;
        }
        
        $closestCity = null;
        $minDistance = PHP_FLOAT_MAX;
        
        foreach ($cities as $city) {
            // Calculate distance using Haversine formula
            $cityLat = $city['latitude'] ?? 0;
            $cityLng = $city['longitude'] ?? 0;
            
            if (!$cityLat || !$cityLng) {
                continue;
            }
            
            $distance = $this->calculateDistance($latitude, $longitude, $cityLat, $cityLng);
            
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestCity = $city;
            }
        }
        
        return $closestCity;
    }
    
    /**
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Filter attractions by city ID
     */
    private function filterByCity($items, $cityId)
    {
        if ($cityId === null) {
            return [];
        }
        
        return array_filter($items, function($item) use ($cityId) {
            return isset($item['city_id']) && $item['city_id'] == $cityId;
        });
    }
    
    /**
     * Generate business attractions with BusinessParent structure
     */
    private function generateAIAttractions($latitude, $longitude, $cityName = null)
    {
        // For Vatra Dornei, generate specific attractions
        if ($cityName === 'Vatra Dornei') {
            return $this->getVatraAttractions($latitude, $longitude);
        }
        
        // For other locations, generate generic attractions
        return $this->getGenericAttractions($latitude, $longitude, $cityName);
    }
    
    /**
     * Generate mountain trails around cities, not in cities
     */
    private function generateMountainTrails($latitude, $longitude, $cityName = null)
    {
        // Check if it's Vatra Dornei - priority location
        if ($cityName && $cityName == 'Vatra Dornei') {
            return $this->getVatraTrails();
        }
        
        // Generate one trail for current location
        return $this->getCurrentLocationTrail($latitude, $longitude, $cityName);
    }

    /**
     * Get mountain trails for Vatra Dornei area
     */
    private function getVatraTrails()
    {
        return [
            [
                'name' => "Rarău Peak Trail",
                'description' => "Classic mountain trail to Rarău Peak with stunning views over Moldova valley.",
                'difficulty' => 'medium',
                'length_km' => 8.5,
                'elevation_gain' => 650,
                'estimated_time' => 300,
                'start_point_lat' => 47.3420,
                'start_point_lng' => 25.3850,
                'end_point_lat' => 47.3580,
                'end_point_lng' => 25.4020,
                'coordinates' => json_encode([
                    ['lat' => 47.3420, 'lng' => 25.3850],
                    ['lat' => 47.3500, 'lng' => 25.3920],
                    ['lat' => 47.3580, 'lng' => 25.4020]
                ]),
                'region' => 'Rarău Mountains',
                'trail_type' => 'hiking',
                'season' => 'all',
                'markers' => json_encode([
                    ['lat' => 47.3420, 'lng' => 25.3850, 'type' => 'start', 'name' => 'Parking'],
                    ['lat' => 47.3580, 'lng' => 25.4020, 'type' => 'end', 'name' => 'Rarău Peak']
                ])
            ],
            [
                'name' => "Giumalău Circuit",
                'description' => "Circular trail around Giumalău mountain with diverse forest landscapes.",
                'difficulty' => 'easy',
                'length_km' => 5.2,
                'elevation_gain' => 320,
                'estimated_time' => 180,
                'start_point_lat' => 47.3200,
                'start_point_lng' => 25.3600,
                'end_point_lat' => 47.3200,
                'end_point_lng' => 25.3600,
                'coordinates' => json_encode([
                    ['lat' => 47.3200, 'lng' => 25.3600],
                    ['lat' => 47.3250, 'lng' => 25.3650],
                    ['lat' => 47.3280, 'lng' => 25.3580],
                    ['lat' => 47.3200, 'lng' => 25.3600]
                ]),
                'region' => 'Giumalău Forest',
                'trail_type' => 'hiking',
                'season' => 'all',
                'markers' => json_encode([
                    ['lat' => 47.3200, 'lng' => 25.3600, 'type' => 'start', 'name' => 'Forest Entry']
                ])
            ]
        ];
    }

    /**
     * Get one mountain trail for current location
     */
    private function getCurrentLocationTrail($latitude, $longitude, $cityName = null)
    {
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $location = $cityName ?? "your area";
        
        return [
            [
                'name' => "Nature Trail near $location",
                'description' => "A peaceful mountain trail with forest paths and scenic viewpoints.",
                'difficulty' => 'medium',
                'length_km' => 4.8,
                'elevation_gain' => 280,
                'estimated_time' => 150,
                'start_point_lat' => round($lat - 0.008, 6),
                'start_point_lng' => round($lng + 0.012, 6),
                'end_point_lat' => round($lat + 0.005, 6),
                'end_point_lng' => round($lng + 0.020, 6),
                'coordinates' => json_encode([
                    ['lat' => round($lat - 0.008, 6), 'lng' => round($lng + 0.012, 6)],
                    ['lat' => round($lat - 0.003, 6), 'lng' => round($lng + 0.016, 6)],
                    ['lat' => round($lat + 0.005, 6), 'lng' => round($lng + 0.020, 6)]
                ]),
                'region' => "$location Mountains",
                'trail_type' => 'hiking',
                'season' => 'all',
                'markers' => json_encode([
                    ['lat' => round($lat - 0.008, 6), 'lng' => round($lng + 0.012, 6), 'type' => 'start', 'name' => 'Trailhead'],
                    ['lat' => round($lat + 0.005, 6), 'lng' => round($lng + 0.020, 6), 'type' => 'end', 'name' => 'Viewpoint']
                ])
            ]
        ];
    }

    /**
     * Get specific attractions for Vatra Dornei with Business structure
     */
    private function getVatraAttractions($latitude, $longitude)
    {
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        
        return [
            [
                'id' => 1001,
                'city_id' => 1,
                'name' => 'Pensiunea Rarău',
                'latitude' => 47.3475,
                'longitude' => 25.3594,
                'logo' => null,
                'google_page_url' => 'https://maps.google.com',
                'rating' => 4.3,
                'address' => 'Strada Principală 15, Vatra Dornei',
                'email' => 'contact@rarau.ro',
                'phone' => '0230-123456',
                'reviews' => 'Pensiune confortabilă cu vedere la munte',
                'favourite' => 0,
                'description' => 'Pensiune tradițională în inima Carpaților cu camere confortabile și restaurant.',
                'program' => 'Program complet',
                'opening_hour' => '08:00',
                'closing_hour' => '22:00'
            ],
            [
                'id' => 1002,
                'city_id' => 1,
                'name' => 'Restaurant Casa Bunicii',
                'latitude' => 47.3450,
                'longitude' => 25.3580,
                'logo' => null,
                'google_page_url' => 'https://maps.google.com',
                'rating' => 4.7,
                'address' => 'Bulevardul Bucovina 22, Vatra Dornei',
                'email' => 'info@casabunicii.ro',
                'phone' => '0230-789123',
                'reviews' => 'Mâncare tradițională excelentă',
                'favourite' => 0,
                'description' => 'Restaurant cu bucătărie tradițională moldovenească și atmosferă caldă.',
                'program' => 'Zilnic',
                'opening_hour' => '10:00',
                'closing_hour' => '23:00'
            ],
            [
                'id' => 1003,
                'city_id' => 1,
                'name' => 'Centrul de Informare Turistică',
                'latitude' => 47.3465,
                'longitude' => 25.3605,
                'logo' => null,
                'google_page_url' => 'https://maps.google.com',
                'rating' => 4.1,
                'address' => 'Piața Centrală 1, Vatra Dornei',
                'email' => 'turism@vatradornei.ro',
                'phone' => '0230-456789',
                'reviews' => 'Personal amabil și informativ',
                'favourite' => 0,
                'description' => 'Centru de informare pentru turiști cu ghiduri locale și hărți.',
                'program' => 'Luni-Vineri',
                'opening_hour' => '09:00',
                'closing_hour' => '17:00'
            ]
        ];
    }

    /**
     * Get generic attractions for other locations with Business structure
     */
    private function getGenericAttractions($latitude, $longitude, $cityName = null)
    {
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $location = $cityName ?? 'această zonă';
        
        return [
            [
                'id' => 2001,
                'city_id' => null,
                'name' => "Restaurant Local $location",
                'latitude' => round($lat + 0.001, 6),
                'longitude' => round($lng + 0.002, 6),
                'logo' => null,
                'google_page_url' => 'https://maps.google.com',
                'rating' => 4.2,
                'address' => "Strada Centrală, $location",
                'email' => 'contact@restaurant.ro',
                'phone' => '0200-123456',
                'reviews' => 'Restaurant cu mâncare locală',
                'favourite' => 0,
                'description' => 'Restaurant cu specific local și mâncare tradițională.',
                'program' => 'Zilnic',
                'opening_hour' => '11:00',
                'closing_hour' => '22:00'
            ],
            [
                'id' => 2002,
                'city_id' => null,
                'name' => "Pensiunea $location",
                'latitude' => round($lat - 0.002, 6),
                'longitude' => round($lng + 0.001, 6),
                'logo' => null,
                'google_page_url' => 'https://maps.google.com',
                'rating' => 4.0,
                'address' => "Bulevardul Principal, $location",
                'email' => 'rezervari@pensiunea.ro',
                'phone' => '0200-789123',
                'reviews' => 'Cazare confortabilă',
                'favourite' => 0,
                'description' => 'Pensiune confortabilă pentru turiști în călătorie.',
                'program' => 'Non-stop',
                'opening_hour' => '00:00',
                'closing_hour' => '23:59'
            ]
        ];
    }
}
