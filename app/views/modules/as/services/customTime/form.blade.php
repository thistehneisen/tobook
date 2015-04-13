{{ Form::open(['route' => ['as.services.customTime.upsert', $service->id, isset($serviceTime->id) ? $serviceTime->id : null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">{{ trans('as.services.price') }}</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">{{ Settings::get('currency') }}</span>
                {{ Form::text('price', isset($serviceTime->price) ? $serviceTime->price : '', ['class' => 'form-control input-sm', 'id' => 'price']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">{{ trans('as.services.duration') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('during', isset($serviceTime->during) ? $serviceTime->during : 0, ['class' => 'form-control', 'id' => 'during']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="before" class="col-sm-2 control-label">{{ trans('as.services.before') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('before', isset($serviceTime->before) ? $serviceTime->before : 0, ['class' => 'form-control', 'id' => 'before']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="after" class="col-sm-2 control-label">{{ trans('as.services.after') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('after', isset($serviceTime->after) ? $serviceTime->after : 0, ['class' => 'form-control', 'id' => 'after']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.total') }}</label>
        <div class="col-sm-5">
            {{ Form::text('total', isset($serviceTime->length) ? $serviceTime->length : 0, ['class' => 'form-control input-sm', 'id' => 'total', 'disabled'=>'disabled']) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="language-tabs">
                @foreach (Config::get('varaa.languages') as $locale)
                <li role="presentation" class="@if ($locale === App::getLocale()) {{ 'active' }} @endif "><a href="#{{$locale}}" aria-controls="{{$locale}}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="tab-content">
        @foreach (Config::get('varaa.languages') as $locale)
            <div role="tabpanel" class="tab-pane @if ($locale === App::getLocale()) {{ 'active' }} @endif" id="{{ $locale }}">
               <div class="form-group">
                    <label for="descriptions" class="col-sm-2 control-label">{{ trans('as.services.categories.description') }}</label>
                    <div class="col-sm-5">
                        {{ Form::textarea('descriptions[' . $locale .']', !empty($data[$locale]['description']) ? ($data[$locale]['description']) : '', ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
