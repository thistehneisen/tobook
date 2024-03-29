@if ($businessLomake) {{ $businessLomake->open(['id' => 'business-form']) }}
    <h3 class="comfortaa orange">{{ trans('user.profile.business') }}</h3>

    @foreach ($businessLomake->fields as $field)
        @include('varaa-lomake::fields.group')
    @endforeach

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <input type="hidden" name="tab" value="business">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
            <a target="_blank" href="{{ $business->business_url }}" class="btn btn-default btn-lg pull-right">{{ trans('user.business.preview') }}</a>
        </div>
    </div>
{{ $businessLomake->close() }}
@endif
