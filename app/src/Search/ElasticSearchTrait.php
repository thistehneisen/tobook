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
     * By default, all fillable fields should be searchable.
     * If there're some special needs for a particular model, developers are
     * encouraged to reimplement this method by themselves.
     */
    public function getSearchDocument()
    {
        return $this->toArray();
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
