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
        return strtolower(class_basename(get_called_class()));
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocumentId()
    {
        return $this->id;
    }

    /**
     * @{@inheritdoc}
     */
    public function updateSearchIndex(ProviderInterface $provider)
    {
        // If this model is not searchable, return as soon as possible
        if ($this->isSearchable === false) {
            return;
        }

        $params = [];
        $params['index'] = $this->getSearchIndexName();
        $params['type']  = $this->getSearchIndexType();
        $params['id']    = $this->getSearchDocumentId();
        $params['body']  = $this->getSearchDocument();

        return $provider->index($params);
    }
}
