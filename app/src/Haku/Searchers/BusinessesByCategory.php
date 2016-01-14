<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;
use App;

class BusinessesByCategory extends Businesses
{
    use BusinessTransformer;

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

    public function getPostFilter()
    {
        return [
            'bool' => [
                'must' =>[
                    [
                        'term' => [
                            'has_discount' =>  $this->getParam('has_discount')
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
        return [
            'bool' => [
                'should' => [
                    ['match' => ['city' => $this->getParam('city')]]
                ],
                'must' => [
                    [
                        'match' => [
                            'master_categories' => $this->getParam('keyword')
                        ],
                    ]
                ]
            ]
        ];
    }
}
