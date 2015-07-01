<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByDistrict extends Businesses
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
                            'district' => $this->getParam('keyword')
                        ]
                    ],
                ]
            ]
        ];
    }
}
