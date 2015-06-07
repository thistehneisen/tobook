<?php namespace App\Haku\Searchers;

use App;

abstract class AbstractSearcher implements SearcherInterface
{
    protected $params;

    abstract public function getIndexName();
    abstract public function getType();
    abstract public function getQuery();
    abstract public function getSort();

    public function __construct($params = [])
    {
        if (!isset($params['keyword'])) {
            throw new \InvalidArgumentException('There must be a `keyword` member in parameter array');
        }

        $this->setParams($params);
        $this->params['from'] = array_get($params, 'from', 0);
        $this->params['size'] = array_get($params, 'size', 15);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * The abstract transformer do nothing, just return the raw result
     *
     * @param array $raw Result from ES
     *
     * @return array
     */
    public function transformResults($raw)
    {
        return $raw;
    }

    /**
     * Should include _source data from ES result. Since we only need _id to
     * lookup in database, _source may be not required by default.
     *
     * @return bool
     */
    protected function shouldReturnSource()
    {
        return false;
    }

    /**
     * Perform search on ES with provided parameters
     *
     * @return array ES result
     */
    public function search()
    {
        $client = App::make('elasticsearch');

        $results = $client->search([
            'index' => $this->getIndexName(),
            'type' => $this->getType(),
            'body' => [
                'from' => array_get($this->params, 'from'),
                'size' => array_get($this->params, 'size'),
                'sort' => $this->getSort(),
                'query' => $this->getQuery(),
                '_source' => $this->shouldReturnSource(),
            ]
        ]);

        return $this->transformResults($results);
    }
}
