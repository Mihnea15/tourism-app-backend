<?php

namespace app\models;

class Trails extends TrailsParent
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
}
