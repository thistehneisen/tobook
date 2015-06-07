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

        $this->params = $params;
    }

    /**
     * Return a pair of $from, $size for pagination
     *
     * @param integer $from
     * @param integer $size
     *
     * @return array
     */
    public function getPagination($from = 0, $size = 15)
    {
        return [$from, $size];
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
     * Perform search on ES with provided parameters
     *
     * @return array ES result
     */
    public function search()
    {
        $client = App::make('elasticsearch');
        list($from, $size) = $this->getPagination();

        $results = $client->search([
            'index' => $this->getIndexName(),
            'type' => $this->getType(),
            'body' => [
                'from' => $from,
                'size' => $size,
                'sort' => $this->getSort(),
                'query' => $this->getQuery(),
            ]
        ]);

        return $this->transformResults($results);
    }
}
