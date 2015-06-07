<?php namespace App\Haku\Searchers;

class BusinessesByDistrict extends AbstractSearcher
{
    public function getIndexName()
    {
        return 'businesses';
    }

    public function getType()
    {
        return 'business';
    }

    public function getQuery()
    {
        return [
            'filtered' => [
                'filter' => [
                    'term' => [
                        'district' => mb_strtolower($this->params['keyword'])
                    ]
                ]
            ]
        ];
    }

    public function getSort()
    {
        return [
            '_geo_distance' => [
                'unit' => 'km',
                'mode' => 'min',
                'order' => 'asc',
                'location' => $this->params['location'],
                'distance_type' => 'sloppy_arc',
            ],
        ];
    }
}
