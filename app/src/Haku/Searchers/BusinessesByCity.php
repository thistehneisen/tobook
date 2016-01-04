<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByCity extends Businesses
{
    use BusinessTransformer;

    public function getQuery()
    {
        return [
            'bool' => [
                'must' => [
                    [
                        'match' => [
                            'master_categories' => $this->getParam('category')
                        ]
                    ],
                    [
                        'match' => [
                            'city' => $this->getParam('keyword')
                        ]
                    ],
                ]
            ]
        ];
    }
}
