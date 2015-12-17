<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByCategory extends Businesses
{
    use BusinessTransformer;

    public function getQuery()
    {
        return [
            'filtered' => [
                'query' => [
                    'match' => [
                        'master_categories' => $this->params['keyword']
                    ],
                    'match' => [
                        'has_discount' => $this->params['has_discount']
                    ],
                ],
                'filter' => [
                    'range' => [
                        'max_price' => [
                            'lte' => $this->params['max_price']
                        ],
                        'min_price' => [
                            'gte' => $this->params['min_price']
                        ]
                    ]
                ]
            ]
        ];
    }
}
