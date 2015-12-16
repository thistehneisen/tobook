<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByCategoryAdvanced extends Businesses
{
    use BusinessTransformer;

    public function getQuery()
    {
        return [
            'bool' => [
                'should' => [
                    ['match' => ['name' => $this->getParam('keyword')]],
                    ['match' => ['keywords' => $this->getParam('keyword')]],
                    ['match' => ['description' => $this->getParam('keyword')]],
                    ['match' => ['city' => $this->getParam('city')]]
                ],
                'must' => [
                    [
                        'match' => [
                            'master_categories' => $this->getParam('category')
                        ],
                    ]
                ],
                "minimum_should_match" => 1
            ]
        ];
    }
}
