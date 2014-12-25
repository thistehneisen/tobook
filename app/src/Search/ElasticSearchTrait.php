<?php namespace App\Search;

use Es, Paginator, Config, Input, App, Log;

trait ElasticSearchTrait
{
    /**
     * Search params customized per request
     *
     * @var array
     */
    protected $customSearchParams = [];

    /**
     * Attach event observers into the model
     *
     * @return void
     */
    protected static function bootElasticSearchTrait()
    {
        // If we're in testing other models, we might not need to trigger ES
        // observers
        if (App::environment() === 'testing') {
            return;
        }

        // Send data of this model to ES for indexing
        static::saved(function ($model) {
            $model->updateSearchIndex();
        });

        // Remove the document index when the model was deleted
        static::deleted(function ($model) {
            $model->deleteSearchIndex();
        });

        // When a trashed model is restored, update its index
        static::restored(function ($model) {
            $model->updateSearchIndex();
        });
    }

    /**
     * Search data with provided keyword
     *
     * @param string $keyword
     *
     * @return Illuminate\Pagination\Paginator
     */
    public static function search($keyword, array $options = [])
    {
        // Use App::make() to create the instance so that mock object could be
        // easily swapped in when testing
        // Because inside a trait, __CLASS__ will return the class name used it,
        // so we need to workaround to get name of inherited class. Possible
        // caveat: memory leak ._.
        $model = App::make(get_class(new static()));

        // First, try to search with search service
        if ($model->isSearchable === true) {
            try {
                return $model->serviceSearch($keyword, $options);
            } catch (\Exception $ex) {
                // Silently failed baby
                Log::error('Failed to search using service: '.$ex->getMessage());
            }
        }

        return $model->databaseSearch($keyword);
    }

    /**
     * Allow model to hook on behaviors of internal search
     *
     * @param Illuminate\Database\Eloquent\QueryBuilder $query
     *
     * @return Illuminate\Database\Eloquent\QueryBuilder
     */
    public function getCustomSearchQuery($query)
    {
        return $query;
    }

    /**
     * Search using ElasticSearch (obviously :|)
     *
     * @author Hung Nguyen <hung@varaa.com>
     *
     * @param string $keyword
     * @param array  $options
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function serviceSearch($keyword, array $options = [])
    {
        $params = $this->buildSearchParams($keyword, $options);
        $provider = App::make('App\Search\ProviderInterface');
        $result = $provider->search($params);

        return Paginator::make(
            $this->transformSearchResult($result['hits']['hits']),
            $result['hits']['total'],
            $params['size']
        );
    }

    /**
     * Search using database
     *
     * @param string $keyword
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function databaseSearch($keyword)
    {
        $model = App::make(get_class(new static()));
        //----------------------------------------------------------------------
        // Fallback to traditional search ._.
        //----------------------------------------------------------------------
        Log::info('Fallback to MySQL search');

        // Get fillable fields of this model
        $fillable = $model->getFillable();

        // Add ID to be candicate for searching
        $fillable[] = 'id';
        $query = static::where(function ($q) use ($fillable, $keyword) {
            foreach ($fillable as $field) {
                $q = $q->orWhere($field, 'LIKE', '%'.$keyword.'%');
            }

            return $q;
        });

        if (method_exists($model, 'scopeOfCurrentUser')) {
            $query = $query->ofCurrentUser();
        }

        $query = $model->getCustomSearchQuery($query);

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        return $query->paginate($perPage);
    }

    /**
     * Build ES params
     *
     * @param string $keywords
     * @param array  $options
     *
     * @return array
     */
    protected function buildSearchParams($keywords, array $options)
    {
        // Default params by trait
        $params = [
            'index' => $this->getSearchIndexName(),
            'type'  => $this->getSearchIndexType(),
            'size'  => Config::get('view.perPage'),
        ];

        // Attach filter and query
        $params['body']['query']['filtered'] = [
            'filter' => $this->buildSearchFilter(),
            'query' => $this->buildSearchQuery($keywords),
        ];

        // Sort the result
        $sortParams = $this->buildSearchSortParams();
        if (!empty($sortParams)) {
            $params['body']['sort'] = $sortParams;
        }

        // Set custom search params by model
        $this->setCustomSearchParams();

        if (!empty($options)) {
            // This allows user to pass custom params for this request only
            // Default params for next requests remain intact
            $params = array_merge($params, $options);
        } elseif (!empty($this->customSearchParams)) {
            // If we don't have specific options for the request, and the model
            // using this trait has set $this->customSearchParams via
            // setCustomSearchParams(), we'll merge them.
            $params = array_merge($params, $this->customSearchParams);
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
    protected function buildSearchFilter()
    {
        $filter = [];

        return $filter;
    }

    /**
     * Options used for sorting results
     *
     * @return array
     */
    protected function buildSearchSortParams()
    {
        return ['_score'];
    }

    /**
     * Build search query based on provided field
     *
     * @param array $fields
     *
     * @return array
     */
    protected function buildSearchQuery($keywords, $fields = null)
    {
        // Since we already has a document mapping, we can just use all fields
        // for matching
        if (empty($fields)) {
            $fields = array_keys($this->getSearchMapping());
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
    protected function setCustomSearchParams()
    {
    }

    /**
     * How the search result will be transform to, for example, Eloquent models.
     * By default, it will auto convert to Eloquent model using `_id` value
     *
     * @param array $results
     *
     * @return array We'll pass the result to Paginator, so no collection required.
     */
    public function transformSearchResult($results)
    {
        $data = [];
        foreach ($results as $result) {
            $item = static::find($result['_id']);
            if ($item !== null) {
                $data[] = $item;
            }
        }

        return $data;
    }

    /**
     * By default, all fillable fields should be searchable.
     * If there're some special needs for a particular model, developers are
     * encouraged to reimplement this method by themselves.
     */
    public function getSearchDocument()
    {
        $data = [];

        $fields = $this->getFillable();
        foreach ($fields as $field) {
            $data[$field] = $this->$field;
        }

        return $data;
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchIndexName()
    {
        return $this->getTable();
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchIndexType()
    {
        return str_singular($this->getSearchIndexName());
    }

    /**
     * @{@inheritdoc}
     */
    public function getSearchDocumentId()
    {
        return $this->id;
    }

    /**
     * Return the schema of this model, used in data mapping of ES while
     * creating new index
     *
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/mapping-analysis.html
     *
     * @return void
     */
    public function getSearchMapping()
    {
        // By default, we will map all fillable fields
        $map = [];

        $fields = $this->getFillable();
        foreach ($fields as $field) {
            $map[$field] = [
                'type' => 'string'
            ];
        }

        return $map;
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

    /**
     * Remove document index
     *
     * @return void
     */
    public function deleteSearchIndex()
    {
        if ($this->isSearchable === false) {
            return;
        }

        $provider = App::make('App\Search\ProviderInterface');

        return $provider->delete([
            'index' => $this->getSearchIndexName(),
            'type'  => $this->getSearchIndexType(),
            'id'    => $this->getSearchDocumentId(),
        ]);
    }
}
