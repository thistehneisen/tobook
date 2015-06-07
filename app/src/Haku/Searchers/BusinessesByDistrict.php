<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;
use App\Haku\Indexers\BusinessIndexer;

class BusinessesByDistrict extends AbstractSearcher
{
    use BusinessTransformer;

    public function getIndexName()
    {
        return BusinessIndexer::INDEX_NAME;
    }

    public function getType()
    {
        return BusinessIndexer::INDEX_TYPE;
    }

    public function getQuery()
    {
        return [
            'filtered' => [
                'query' => [
                    'match' => [
                        'master_categories' => $this->params['category']
                    ]
                ],
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
