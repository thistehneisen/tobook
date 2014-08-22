<?php
/**
 * Add an indication text to to required fields
 *
 */
Form::macro('required', function (
    $field,
    $validator,
    $format = ':star'
) {

    if (array_key_exists($field, $validator->getRules())) {
        return str_replace(':star', '*', $format);
    }

    return '';
});

/**
 * Display error text (compatible with B3)
 */
Form::macro('errorText', function (
    $field,
    \Illuminate\Support\ViewErrorBag $errors
) {

    if ($errors->has($field)) {
        $text = implode('<br>', $errors->get($field, ':message'));

        return '<span class="help-block">'.$text.'</span>';
    }

    return '';
});

/**
 * Return the error CSS class (B3 compatible)
 */
Form::macro('errorCSS', function (
    $name,
    \Illuminate\Support\ViewErrorBag $errors,
    $class = 'has-error'
) {
    if ($errors->has($name)) {
        return $class;
    }

    return '';
});
