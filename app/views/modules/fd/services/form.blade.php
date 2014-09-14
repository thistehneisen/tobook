@extends ('modules.fd.layout')

@section ('content')
    @include('modules.as.crud.tabs', ['routes' => $routes, 'langPrefix' => $langPrefix])

<div id="form-add-category" class="modal-form">
    @include ('el.messages')
    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ Form::open(['route' => [$routes['upsert'], !empty($item->id) ? $item->id : ''], 'class' => 'form-horizontal', 'role' => 'form']) }}

    <div class="form-group {{ Form::errorCSS('business_category_id', $errors) }}">
        <label for="business_category_id" class="col-sm-2 col-sm-offset-1 control-label">{{ trans($langPrefix.'.business_category_id') }} {{ Form::required('business_category_id', $item) }}</label>
        <div class="col-sm-6">
            <select name="business_category_id" id="business_category_id" class="form-control">
                @foreach ($businessCategories as $category)
                    <optgroup label="{{ $category->name }}">
                    @foreach ($category->children as $sub)
                        <option value="{{ $sub->id }}" {{ Input::get('business_category_id', $item->business_category_id) === $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                    @endforeach
                    </optgroup>
                @endforeach
            </select>
            {{ Form::errorText('business_category_id', $errors) }}
        </div>
    </div>

    @foreach ($item->fillable as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        <label for="{{ $field }}" class="col-sm-2 col-sm-offset-1 control-label">{{ trans($langPrefix.'.'.$field) }} {{ Form::required($field, $item) }}</label>
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $item->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::close() }}
</div>
@stop
