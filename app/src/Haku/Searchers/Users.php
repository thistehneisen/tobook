<?php namespace App\Haku\Searchers;

use App\Haku\Transformers\UserTransformer;
use App\Haku\Indexers\UserIndexer;

class Users extends AbstractSearcher
{
    use UserTransformer;

    public function getIndexName()
    {
        return UserIndexer::INDEX_NAME;
    }

    public function getType()
    {
        return UserIndexer::INDEX_TYPE;
    }

    public function getQuery()
    {
        return [
            'bool' => [
                'should' => [
                    ['match' => ['email' => $this->getParam('keyword')]],
                    ['match' => ['name' => $this->getParam('keyword')]],
                ],
            ]
        ];
    }

    public function getSort()
    {
        return ['_score'];
    }
}
