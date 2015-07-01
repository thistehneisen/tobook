<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByName extends Businesses
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
                ],
            ]
        ];
    }
}
