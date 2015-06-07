<?php namespace App\Haku\Searchers\Traits;

trait SortByLocation
{
    public function getSort()
    {
        if (empty($this->params['location'])) {
            // Return empty object
            return new \sdtClass();
        }

        return [
            '_geo_distance' => [
                'unit' => 'km',
                'mode' => 'min',
                'order' => 'desc',
                'location' => $this->params['location'],
                'distance_type' => 'sloppy_arc',
            ],
        ];
    }
}
