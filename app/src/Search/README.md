## Search

The search module of Varaa is powered by [ElasticSearch](http://www.elasticsearch.org).

## ES Searchable Models

The module exposes an `App\Search\ElasticSearchTrait` that have these
methods implemented:

#### `search($keyword)`

`@todo: Support pagination`

#### `getSearchDocument()`

Return an array containing all information that is indexed and searchable by
ElasticSearch.

#### `getSearchIndexName()`

Return a string that is used as index of model. See
http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_index.

#### `getSearchIndexType()`

Return a string that is optinally used as type of the index. See
http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_type

By default, all models are searchable because `App\Search\ElasticSearchTrait`
is implemented in implemented. However, it's possible to disable
searching/indexing by setting `public $isSearchable = false`.


