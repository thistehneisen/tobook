## Haku
##### Finnish for "search", rhyme with "haiku" 俳句

### Introduction
Haku is a new API to provide search functionalities backed by ElasticSearch (ES). It aims to have a flexible structure which allows to define custom search queries at ease.

### Components
#### Indexers
Indexers have responsible for indexing data. Basically they define the index name, type, how an Eloquent model is transformed into an ES document, and how data is [mapping](https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping.html) (schema definitions).

Indexers will perform two actions: `index()` a document and `delete()` a document from ES.

#### Searchers
Searchers define search queries which might be different based on the requirements. Each searcher is a class having a semantic name, for example, `Searchers\BusinessesByDictrict` or `Searchers\UsersByEmail`. Insides, how queries are structured and how the results are sorted and transformed will be defined.

*Example: Search users*
```
$searcher = new UsersSearcher(['keyword' => $keyword]);
$items = $searcher->search();
```

*Example: Search businesses by district name with location supported*
```
$params['keyword'] = Input::get('location');
$params['category'] = 'Thai massage';

$s = new BusinessesByDistrict($params);
$results = $s->search();
```
### Transformers
They are supporting traits that define how raw data from ES will be converted back to appropriate Eloquent models. Searchers could define their own `transformResults()` method, but using a transformer trait improves code re-usage.

### Commands
There are two commands:
`varaa:haku-delete-indexes`: Delete all indexes in ES
`varaa:haku-create-indexes`: Create indexes for business and user data.

### @TODO

- Add tests
