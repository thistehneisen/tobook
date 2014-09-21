Lomake is a simple form builder to help developers quickly create forms.
It utilizes `$fillable` inside model class to create a form.

### Features
- Validation errors supported. Please remember to
`use Watson\Validating\ValidatingTrait;` and define `$rulesets`.
- Various types of controls supported.
  - Password input for fields starting with `password_`
  - Radio input for fields starting with `is_`
- Easily overwrite generated fields or add new fields
- Customize form template

### Create form from Eloquent model
`Lomake::make($model, array $options)`

Where:
* `$model`: it could be an instance of model, or a class name, e.g. `App\Consumers\Models\Consumer`
* `$options`: available keys
  * `route`: _(required)_ the route will be used to open form
  * `form`: as the array passed to `Form::open()`
  * `langPrefix`: prefix to language keys used to translate fields, e.g. `as.services.name`, `as.services.description` => `as.services`
  * `fields`: an array of fields to be added/overwritten
  * `template`: by default, Lomake uses `src/Lomake/views/form.blade.php` to generate. This allows to pass another form template. Please have a look at the default.

**Notes:**
- `route` will overwrite the existing key `route` in `form`

```
$lomake = Lomake::make('App\Consumers\Models\Consumer');
```

```
$item = Consumer::make();
$lomake = Lomake::make($item, [
    'route' => ['as.services.categories.upsert', isset($item) ? $item->id : null],
    'langPrefix' => 'as.services'
]);
```

Display in Blade template
```
{{ $lomake->render() }}
// or shorter
{{ $lomake }}
```

#### Partially render
By default, if you `echo $lomake`, the whole form with all fields will be
rendered. If you want to have custom layout for your form, you can access
individual fields as properties of Lomake object.

For example:
```
$lomake = Lomake::make('App\Core\Models\User', ['route' => '#']);

// Access field by its name
$lomake->first_name;
$lomake->email;
$lomake->password;

// You can also get all fields with
$lomake->fields;
```

#### Add/overwrite generated fields
Lomake allows you to define your set of fields, instead of auto generating from
model. Just define `fields`. Each element in `fields` is as followed:

```
$el = [
  // @see Supported fields secion below
  'type' => 'Dropdown',
  // The values will be use to create field appearance. It could be options
  // for dropdown, a list of values of checkboxes, etc.
  'values' => ['m' => 'Male', 'f' => 'Female'],
  // Default value will be selected/populated on form
  'default' => 'Male',
  // Additional options will be passed when creating. See individual field for
  // more information
  'options' => []
];
```

```
Lomake::make($item, [
    'route' => ['as.services.categories.upsert', isset($item) ? $item->id : null],
    'fields' => [
      'full_name' => [
        'type' => 'Text',
        'default' => 'John Doe'
      ],
      'description' => [
        'type' => 'Textarea'
      ],
      'gender' => [
        'label' => 'Gender', // optional
        'type' => 'Dropdown',
        'values' => ['Male', 'Female']
      ]
    ]
]);
```

#### Supported fields
Lomake comes with some built-in form controls (called `fields`).

##### Text, Textarea, Email
##### Checkbox
##### Radio
##### Dropdown
##### DateTimeDropdown
##### TimezoneDropdown
##### Spinner
_Require some JavaScript_

You can also define your own type, just by extending those based classes. See
`DateTimeDropdown` or `Textarea` for examples.
