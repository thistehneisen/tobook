<?php namespace App\Search;

trait ElasticSearchTrait
{
    /**
     * Set this to `false` to disable searching/indexing in the model
     *
     * @var boolean
     */
    public $isSearchable = true;

    /**
     * @{@inheritdoc}
     */
    public function search($keyword)
    {
        return true;
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocument()
    {

    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchIndexName()
    {
        return str_plural($this->getSearchIndexType());
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchIndexType()
    {
        return strtolower(class_basename(__CLASS__));
    }

}
