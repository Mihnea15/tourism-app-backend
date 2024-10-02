<?php

namespace app\models;

class Business extends BusinessParent
{

    public function getImages()
    {
        return $this->hasMany(BusinessImage::class, ['business_id' => 'id']);
    }
}