<?php namespace App\Haku\Searchers\Traits;

trait SortByLocation
{
    public function getSort()
    {
        if (empty($this->params['location'])) {
            // Return empty object
            return new \sdtClass();
        }

        $location = array_map('doubleval', $this->params['location']);
        list($lat, $lng) = $location;

        if ($lat === 0.0 && $lng === 0.0) {
            return ['_score'];
        }

        return [
            '_geo_distance' => [
                'unit' => 'km',
                'mode' => 'min',
                'location' => [
                    'lat' => $lat,
                    'lon' => $lng,
                ],
                'distance_type' => 'sloppy_arc',
            ],
        ];
    }
}
