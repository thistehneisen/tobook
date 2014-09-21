# Olut, a CRUD trait for Laravel
* * *

Olut is a simple trait attach to Laravel to facilitate boring CRUD tasks. In
 additions to traditional CRUD, it also provides some features such as data
 validations, bulk action, search, filters, etc.

Because less time doing boring stuff, more time to have
:beer: :beer: :beer: Kippis!!!

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

**protip**: `App\Olut\Olut` is already set alias as `Crud` in `app.php`, so you
 can just `use \CRUD`;


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
Your CRUD controller is good to go now. But Olut still provides somes options
for you to customize its behaviors. They are:

```
protected $crudOptions = [
    'layout'      => '',
    'modelClass'  => '',
    'langPrefix'  => '',
    'indexFields' => [],
    'showTab'     => true,
    'lomake'      => [],
    'actionsView' => null,
    'presenters'  => [
        'id' => 'App\Olut\Presenters\Base',
        'is_active' => 'App\Olut\Presenters\Bool',
        'email' => 'App\Olut\Presenters\Email',
    ]
];
```
`layout` (string) *required*

The layout that Olut will extend. If not available, Olut will use its ugly
default layout instead.

`modelClass` (string) *optional*

Olut is dumb enough to figure out which model you're trying to use. But in case
it's too dumb, set the name of your model with this option (remeber to include
    namespace if available)

`langPrefix` (string) *optional*

The prefix of language keys will be used.

`indexFields` (array) *optional*

By default, Olut will show all fillable fields of your model (defined with
    `$fillable`). If you want to limit or add new fields, use this option.

`showTab` (bool) *optional*

Should Olut show the tab to switch between list of items and upsert form.

`lomake` (array) *optional*

Options will be passed to Lomake to generate form.

`actionsView` (string) *optional*

Path to the view file containing additional actions in list of result view. For
example, `admin.users.actions` and its content:

```
<a href="{{ route('admin.users.login', ['id'=> $item->id ]) }}" class="btn btn-xs btn-warning" title="Login as this user"><i class="fa fa-hand-o-up"></i></a>
```

`presenters` (array) *optional*

This is how your fields will be display in the index list. The keys of this
 array match with the name of your fields, and its values is the presenter
 class. More about this later.

# Setup `upsertHandler`
By default, Olut just takes all input and fill them into your model. If you want
to have complex logic (most models have), for examples, updating relationship,
sending email, etc., define your own `upsertHandler`.

```
    protected function upsertHandler($item)
    {
        $service = Service::findOrFail(Input::get('service_id'));

        $item->fill(Input::all());
        $item->service()->associate($service);
        $item->user()->associate(Confide::user());

        // Call saveOrFail() to execute validation also
        $item->saveOrFail();

        // Remember to return the item
        return $item;
    }
```

# Customize the list of items
There are cases that you want to display the data differently than plain text,
for examples, showing avatar of users, displaying data of other related models,
    etc. Olut allows you to do this by faciliating `indexFields` and `presenters`
in `$crudOptions`.

Assume that your model has those fillable fields:
```
    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postcode',
        'country',
        'is_active'
    ];
```

By defining your `indexFields`
```
    'indexFields' => [
        'first_name',
        'last_name',
        'email',
        'phone',
    ]
```

If you want to add link to your emails (although Olut already did it for you),
define your `presenters` like this:
```
'presenters' => [
    'email' => 'App\Olut\Presenters\Email'
]
```

Your list of items will shown only those fields and emails will be hyperlinked.
You are free to define new presenter class, as long as it extends from
`App\Olut\Presenters\Base`. Have a look at that class for more details.

You can also add new columns to the list and set presenter for that custom
columns.

# Customize form
Form is generated by Lomake, so you could use `lomake` option in `$crudOptions`
to pass the desired bahaviors.

In previous example, if we want to display a Radio input for field `is_active`
```
'lomake' => [
    'is_active' => [
        'type' => 'Radio'
    ]
]
```
Consult Lomake's README for more information.

Inside your custom form view, there's a special variable `$lomake`. You could
use this to partially get controls and place them as you want to get better
layout. Example:

```
<div class="form-group">
    <div class="col-sm-4">{{ $lomake->first_name->getLabel() }}</div>
    <div class="col-sm-8">{{ $lomake->first_name }}</div>
</div>

<div class="form-group">
    <div class="col-sm-9">{{ $lomake->last_name->getLabel() }}</div>
    <div class="col-sm-3">{{ $lomake->last_name }}</div>
</div>
```


# Overwrite default views
Olut will auto aware if there are views named `index` and `form` in your
`$viewPath`, it will use those instead of the default ones. This allows you
to overwrite the default view if its flexibility doesn't satisfy you enough.


