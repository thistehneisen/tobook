# Olut, a CRUD trait for Laravel
* * *

Olut is a simple trait attach to Laravel to facilitate boring CRUD tasks. In additions to traditional CRUD, it also provides some features such as data validations, bulk action, search, filters, etc.

# How to install?
The ideas behind Olut are simple and straightforward:

1. Define to use Olut inside the controller
2. Generate routes for CRUD actions
3. Set some options
4. Done

### Define to use Olut inside the controller
First, define to use Olut as following:

```
<?php namespace App\Core\Controllers;

class Test extends Base
{
    use \App\Olut\Olut;
}
```

**protip**: `App\Olut\Olut` is already set alias as `Crud` in `app.php`, so you can just `use \CRUD`;


### Generate routes
Inside `routes.php`, call as following:

```php
\App\Core\Controllers\Test::crudRoutes(
    'test', // The URI that guides to this controller
    'common' // Prefix for the name of all generated routes
);
```

It will automatically generate those routes: <name - URI>
```
common.index => /test
common.upsert => /test/upsert/{id?}
common.delete => /test/delete/{id}
```

Use the name of those routes to generate URLs.

### Set options
Your CRUD controller is good to go now. But Olut still provides somes options for you to customize its behaviors. They are:

```
protected $crudOptions = [
    'modelClass' => '',
    'layout' => '',
    'langPrefix' => '',
    'indexFields' => [],
    'presenters' => [
        'id' => 'App\Olut\Presenters\Base',
        'is_active' => 'App\Olut\Presenters\Bool',
        'email' => 'App\Olut\Presenters\Email',
    ]
];
```

`modelClass` (string) *optional*

Olut is dumb enough to figure out which model you're trying to use. But in case it's too dumb, set the name of your model with this option (remeber to include namespace if available)

`layout` (string) *optional*

The layout that Olut will extend

`langPrefix` (string) *optional*

The prefix of language keys will be used.

`indexFields` (array) *optional*

By default, Olut will show all fillable fields of your model (defined with `$fillable`). If you want to limit or add new fields, use this option.

`presenters` (array) *optional*

This is how your fields will be display in the index list. The keys of this array match with the name of your fields, and its values is the presenter class. More about this later.


