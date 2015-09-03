<div id="form-add-extra-service">
    <form action="">
        <div class="form-group">
            <label>{{ trans('as.services.extras.msg_extra') }}</label>
            @foreach ($extraServices as $extraService)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="extra_services[]" value="{{ $extraService->id }}"> {{ $extraService->name }} ({{ $extraService->length }} {{ trans('common.minutes')}})
                </label>
            </div>
            @endforeach
        </div>

        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="service_id" value="{{ $service->id }}">
        <input type="hidden" name="service_time" value="{{ $serviceTime }}">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">{{ trans('common.next') }}</button>
        </div>
    </form>
</div>
