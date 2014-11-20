<?php namespace App\Search;

interface SearchableInterface
{
    /**
     * Search data based on the provided keyword
     *
     * @param string $keyword
     *
     * @return Illuminate\Support\Collection
     */
    public function search($keyword);

    /**
     * Get document (in associate array) to be indexed in ElasticSearch
     *
     * @return array
     */
    public function getSearchDocument();

    /**
     * Return the name that is used as index name in ElasticSearch
     *
     * @see  http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_index
     * @return string
     */
    public function getSearchIndexName();

    /**
     * Return the name that is used as type in ElasticSearch
     *
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_type     *
     * @return string
     */
    public function getSearchIndexType();

    /**
     * Return the ID used for `_id` in ElasticSearch
     *
     * @return string
     */
    public function getSearchDocumentId();

    /**
     * Call to update index
     *
     * @param App\Search\ProviderInterface $client
     *
     * @return void
     */
    public function updateSearchIndex(ProviderInterface $client);
}
