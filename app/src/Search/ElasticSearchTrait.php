<?php namespace App\Search;

use Es, Paginator, Config, Input, App;

trait ElasticSearchTrait
{
    /**
     * Set this to `false` to disable searching/indexing in the model
     *
     * @var boolean
     */
    public $isSearchable = true;

    /**
     * Search params customized per request
     *
     * @var array
     */
    protected static $customSearchParams = [];

    /**
     * Attach event observers into the model
     *
     * @return void
     */
    protected static function bootElasticSearchTrait()
    {
        // Send data of this model to ES for indexing
        static::saved(function ($model) {
            $model->updateSearchIndex();
        });
    }

    /**
     * Search using ElasticSearch (obviously :|)
     *
     * @author Hung Nguyen <hung@varaa.com>
     *
     * @param string $keywords
     * @param array  $options
     *
     * @return Illuminate\Pagination\Paginator
     */
    public static function serviceSearch($keywords, array $options = [])
    {
        $params = static::buildSearchParams($keywords, $options);
        $provider = App::make('App\Search\ProviderInterface');
        $result = $provider->search($params);

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
        // Default params by trait
        $params = [
            'index' => static::getSearchIndexName(),
            'type'  => static::getSearchIndexType(),
            'size'  => Config::get('view.perPage'),
        ];

        // Attach filter and query
        $params['body']['query']['filtered'] = [
            'filter' => static::buildSearchFilter(),
            'query' => static::buildSearchQuery($keywords)
        ];

        // Set custom search params by model
        static::setCustomSearchParams();

        if (!empty($options)) {
            // This allows user to pass custom params for this request only
            // Default params for next requests remain intact
            $params = array_merge($params, $options);
        } elseif (!empty(static::$customSearchParams)) {
            // If we don't have specific options for the request, and the model
            // using this trait has set static::$customSearchParams via
            // setCustomSearchParams(), we'll merge them.
            $params = array_merge($params, static::$customSearchParams);
        }

        // Pagination
        $size = $params['size'];
        $params['from'] = Input::get('page', 1) * $size - $size;

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
     * Model using the trait could overwrite this method to set some default
     * params for searching, for example, the default `size`
     */
    protected static function setCustomSearchParams()
    {
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
    public function updateSearchIndex()
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

        $provider = App::make('App\Search\ProviderInterface');

        return $provider->index($params);
    }
}
