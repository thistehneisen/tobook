@foreach ($values as $value => $label)
<div class="checkbox">
    <label>
        {{ Form::checkbox($name.'[]', $value, in_array($value, $default)) }} {{ $label }}
    </label>
</div>
@endforeach
