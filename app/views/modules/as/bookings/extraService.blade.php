<div id="add-extra-service-form" class="as-calendar-extra-service">
    <h2>{{ trans('as.services.extra') }}</h2>
    <form id="add_extra_service_form" action="">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
            {{ Form::select('extra_services[]', $extraServices, 0 , array('class'=> 'selectpicker form-control input-sm','multiple' => true)); }}
            </select>
            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
            <a href="#" id="btn-add-extra-service" data-action-url="{{ route('as.bookings.add-extra-services')}}" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
            </div>
        </div>
    </div>
    </form>
</div>
<script type="text/javascript">
    $('.selectpicker').selectpicker();
</script>
