@foreach ($values as $value => $label)
<div class="checkbox">
    <label>
        {{ Form::radio($name.'[]', $value, $value == $default) }} {{ $label }}
    </label>
</div>
@endforeach
