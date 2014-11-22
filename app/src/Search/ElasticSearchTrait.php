<?php namespace App\Search;

use Es, Paginator, Config, Input;

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
     *
     * @author Hung Nguyen <hung@varaa.com>
     */
    public static function search($keywords, array $options = [])
    {
        $params = static::buildSearchParams($keywords, $options);
        $result = Es::search($params);

        return Paginator::make(
            static::transformSearchResult($result['hits']['hits']),
            $result['hits']['total'],
            $params['size']
        );
    }

    /**
     * Build ES params
     *
     * @param string $keywords
     * @param array  $options
     *
     * @return array
     */
    protected static function buildSearchParams($keywords, array $options)
    {
        // Default params
        $size = Config::get('view.perPage');
        $params = [
            'index' => static::getSearchIndexName(),
            'type'  => static::getSearchIndexType(),
            'from'  => Input::get('page', 1) * $size - $size,
            'size'  => $size,
        ];

        // Attach filter and query
        $params['body']['query']['filtered'] = [
            'filter' => static::buildSearchFilter(),
            'query' => static::buildSearchQuery($keywords)
        ];

        // Merge default options with user's option
        if (!empty($options)) {
            $params = array_merge($params, $options);
        }

        return $params;
    }

    /**
     * Return an array containing compatible ES filter parameters
     *
     * @return array
     */
    protected static function buildSearchFilter()
    {
        $filter = [];

        return $filter;
    }

    /**
     * Build search query based on provided field
     *
     * @param array $fields
     *
     * @return array
     */
    protected static function buildSearchQuery($keywords, $fields = null)
    {
        if (empty($fields)) {
            $instance = new static();
            $fields = $instance->getFillable();
        }

        $query = [];
        foreach ($fields as $field) {
            $query['bool']['should'][]['match'][$field] = $keywords;
        }

        return $query;
    }

    /**
     * How the search result will be transform to, for example, Eloquent models.
     * By default, it does nothing.
     *
     * @param array $result
     *
     * @return array
     */
    public static function transformSearchResult($result)
    {
        return $result;
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
    public static function getSearchIndexName()
    {
        return str_plural(static::getSearchIndexType());
    }

    /**
     * @{@inheritdoc}
     */
    public static function getSearchIndexType()
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
