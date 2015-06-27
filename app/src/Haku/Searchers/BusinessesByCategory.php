<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\BusinessTransformer;

class BusinessesByCategory extends AbstractSearcher
{
    use Traits\Business;
    use Traits\SortByLocation;
    use BusinessTransformer;

    public function getQuery()
    {
        return [
            'filtered' => [
                'query' => [
                    'match' => [
                        'master_categories' => $this->params['keyword']
                    ]
                ]
            ]
        ];
    }
}
