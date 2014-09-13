@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.add') }}</strong></p>
    <p>{{ trans('as.services.add_desc') }}</p>
</div>
<div id="form-add-category" class="modal-form">
{{ Form::open(['route' => 'as.services.create', 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">{{ trans('as.services.name') }}</label>
        <div class="col-sm-5">
            <input type="text" class="form-control input-sm" id="name">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
        <div class="col-sm-5">
            <textarea class="form-control input-sm" id="description"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">{{ trans('as.services.price') }}</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                <input type="text" class="form-control input-sm" placeholder="">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">{{ trans('as.services.duration') }}</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="duration">
        </div>
    </div>
    <div class="form-group">
        <label for="before" class="col-sm-2 control-label">{{ trans('as.services.before') }}</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="before">
        </div>
    </div>
    <div class="form-group">
        <label for="after" class="col-sm-2 control-label">{{ trans('as.services.after') }}Jälkeen</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="after">
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.total') }}</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="total" value="0" disabled>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label><input type="checkbox"> {{ trans('as.services.is_active') }}?</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.category') }}</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.resource') }}</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.extra') }}</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
                <option value="">{{ trans('as.services.category') }} 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">{{ trans('as.services.employees') }}</label>
        <div class="col-sm-5">
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for=""><input type="checkbox"> {{ trans('as.bookings.employee') }} 1</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <select name="" id="" class="form-control input-sm">
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for=""><input type="checkbox"> {{ trans('as.bookings.employee') }} 1</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <select name="" id="" class="form-control input-sm">
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                        <option value="">{{ trans('as.services.category') }} 1</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
</div>
@stop
