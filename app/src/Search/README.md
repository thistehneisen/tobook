## Deprecated. Use `App\Haku` instead.
----------
## Search

The search module of Varaa is powered by [ElasticSearch](http://www.elasticsearch.org).

## ES Searchable Models

A model must implement `App\Search\SearchableInterface` so that method
`search($keyword, array $options)` becomes visible. Trait
`App\Search\ElasticSearchTrait` has already had some methods of
`SearchableInterface`. This helps to facilitate the work of implementing the
interface, as well as to allow customize per model.

By default, all models extended from `AppModel` (or `App\Core\Models\Base` to
be exact) are searchable. However, it's possible to disable
searching/indexing by setting `public $isSearchable = false`.

How a document is indexed and what data is defined by method `getSearchDocument()`.
By default `$model->toArray()` is used.

## Search Service Providers

ES is considered as one of available search service. Its methods of indexing,
searching, deleting, etc. are wrapped insides `App\Search\ProviderInterface`.
There is `app/ioc.php` for IoC binding. By using that, we could easily swap
new search service in the future, and tests become less stressful.

## Build Search Indices command

`php artisan varaa:build-search-indices [--models=App\Core\Models\User,App\FlashDeal\Models\Service]`

Command to rebuild indices of one/multiple models. If there's no `--models`
provided, all default models are reindexed. The job is, to pull out all records
of that model in database, and push to the default queue to rebuild the index.

For this command to work, the default queue should be run

`nohup php /path/to/artisan queue:work --daemon > /dev/null 2>&1 &`

## Remove all indices in ES

`curl -XDELETE '127.0.0.1:9200/_all'`
