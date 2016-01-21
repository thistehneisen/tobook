<?php namespace App\Haku\Searchers;

use App\Core\Models\Business;
use Paginator;
use App;

class BusinessesByCategory extends Businesses
{

     /**
     * Perform search on ES with provided parameters
     *
     * @return array ES result
     */
    public function search()
    {
        $client = App::make('elasticsearch');

        $results = $client->search([
            'index' => $this->getIndexName(),
            'type' => $this->getType(),
            'body' => [
                'from' => array_get($this->params, 'from'),
                'size' => array_get($this->params, 'size'),
                'sort' => $this->getSort(),
                'query' => $this->getQuery(),
                'post_filter' => $this->getPostFilter(),
                '_source' => $this->shouldReturnSource(),
            ]
        ]);

        return $this->transformResults($results);
    }

    public function transformResults($results)
    {
        $items = [];
        $location = $this->getParam('city');

        foreach ($results['hits']['hits'] as $item) {
            $business = null;

            if (empty($location)) {
                $business = Business::ofUser($item['_id'])->first();
            } else {
                $business = Business::ofUser($item['_id'])->where('city', '=', $location)
                        ->orWhere('district', '=', $location)->first();
            }
            
            if ($business !== null) {
                $items[] = $business;
            }
        }

        return Paginator::make(
            $items,
            $results['hits']['total'],
            array_get($this->params, 'size')
        );
    }

    public function getPostFilter()
    {
        return [
            'bool' => [
                'must' =>[
                    [
                        'term' => [
                            'has_discount' =>  $this->getParam('has_discount'),
                        ]
                    ],
                    [
                        'range' => [
                            'prices' => [
                                'lte' => $this->params['max_price'],
                                'gte' => $this->params['min_price']
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getQuery()
    {   
        $query = [ 
            'bool' => [
                'must' => [
                    [
                        'match' => [
                            'master_categories' => $this->getParam('keyword'),
                        ]
                    ]
                ]
            ]
        ];

        if (!empty($this->getParam('city'))) {
            $query = [ 
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'master_categories' => $this->getParam('keyword'),
                            ],
                            'match' => [
                                'city_district' => $this->getParam('city')
                            ],
                        ]
                    ]
                ]
            ];
        }
        return $query;
    }
}
