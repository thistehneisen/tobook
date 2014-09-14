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

    {{ Form::open(['route' => ['fd.flash_deals.upsert', !empty($item->id) ? $item->id : ''], 'class' => 'form-horizontal', 'role' => 'form']) }}

    <div class="form-group {{ Form::errorCSS('service_id', $errors) }}">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.flash_deals.service') }} {{ Form::required('service_id', $item) }}</label>
        <div class="col-sm-6">
            {{ Form::select('service_id', array_combine($services->lists('id'), $services->lists('name')), Input::get('service_id', $item->service_id), ['class' => 'form-control']) }}
            {{ Form::errorText('service_id', $errors) }}
        </div>
    </div>

    @foreach ($item->fillable as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        <label for="{{ $field }}" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.flash_deals.'.$field) }} {{ Form::required($field, $item) }}</label>
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $item->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
    @endforeach

    <h4 class="comfortaa">{{ trans('fd.flash_deals.existing_dates') }}</h4>
    <table class="table table-hovered table-stripped">
        <thead>
            <tr>
                <th>{{ trans('fd.flash_deal_dates.date')  }}</th>
                <th>{{ trans('fd.flash_deal_dates.time')  }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($item->dates as $date)
            <tr id="js-fd-date-{{ $date->id }}">
                <td>{{ $date->date }}</td>
                <td>{{ $date->time }}</td>
                <td><a data-confirm="{{ trans('common.are_you_sure') }}" data-id="{{ $date->id }}" href="{{ route('fd.flash_deal_dates.delete', ['id' => $date->id]) }}" class="js-fd-delete-date text-danger"><i class="fa fa-close"></i></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4 class="comfortaa">{{ trans('fd.flash_deals.dates') }}</h4>

    <div class="form-group js-fd-date" id="js-fd-date-template">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.flash_deals.date') }}</label>
        <div class="col-sm-6">
            <?php $uuid = uniqid(); ?>
            {{ Form::text('date['.$uuid.']', Input::get('date', $item->date), ['class' => 'form-control date-picker']) }}
            {{ Form::errorText('date', $errors) }}

            <div class="fd-flash-deal-times" data-toggle="buttons">
            <?php $now = new Carbon\Carbon('08:45:00'); ?>
            @for ($i = 0; $i <= 36; $i++)
                <?php $value = $now->addMinutes(15)->format('H:i'); ?>
                <label class="btn btn-default">
                    <input type="radio" name="time[{{ $uuid }}]" value="{{ $value }}"> {{ $value }}
                </label>
            @endfor
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <p><a href="#" id="js-fd-add-date">{{ trans('fd.flash_deals.add_date') }}</a></p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::close() }}
</div>
@stop
