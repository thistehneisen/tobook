<?php namespace App\Haku\Searchers\Traits;

use App\Haku\Indexers\BusinessIndexer;

trait Business
{
    public function getIndexName()
    {
        return BusinessIndexer::INDEX_NAME;
    }

    public function getType()
    {
        return BusinessIndexer::INDEX_TYPE;
    }

}
