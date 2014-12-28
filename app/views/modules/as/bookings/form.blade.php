<div id="book-form" class="@if(isset($upsert)) as-edit-booking @else as-calendar-book @endif">
@if(empty($booking))
<h2>{{ trans('as.bookings.add') }} <span id="loading" style="display:none"><img src="{{ asset_path('core/img/busy.gif') }}"/></span></h2>
@else
<h2>{{ trans('as.bookings.edit') }} <span id="loading" style="display:none"><img src="{{ asset_path('core/img/busy.gif') }}"/></span></h2>
@endif
<form id="booking_form" method="POST" action="{{ route('as.bookings.add') }}">
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
                <h4 class="panel-title" id="panel-booking-info-handle">
                    1. {{ trans('as.bookings.booking_info') }}
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            @if (isset($booking))
                            <div class="form-group row">
                                <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('common.created_at') }}</label>
                                <div class="col-sm-8">{{ $booking->created_at->format('d-m-Y H:i:s') }}</div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('as.bookings.booking_id') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('uuid', $uuid , ['class' => 'form-control input-sm', 'id' => 'uuid', 'disabled'=> 'disabled']) }}
                                    <input type="hidden" name="booking_uuid" id="booking_uuid" value="{{ $uuid }}"/>
                                    <input type="hidden" name="booking_id" id="booking_id" value="{{ (!empty($booking) ? $booking->id : '') }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="booking_status" class="col-sm-4 control-label">{{ trans('as.bookings.status') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::select('booking_status', $bookingStatuses, (isset($booking)) ? $booking->getStatusText() : '', ['class' => 'form-control input-sm', 'id' => 'booking_status']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="booking_notes" class="col-sm-4 control-label">{{ trans('as.bookings.notes') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::textarea('booking_notes', (isset($booking)) ? $booking->notes : '', ['class' => 'form-control input-sm', 'id' => 'booking_notes']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="keyword" class="col-sm-4 control-label">{{ trans('as.bookings.keyword') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('keyword', '', ['class' => 'form-control input-sm select2', 'id' => 'keyword']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="consumer_first_name" class="col-sm-4 control-label">{{ trans('as.bookings.first_name') }}  {{ Form::required('first_name', with(new Consumer)) }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('first_name', (isset($booking)) ? $booking->consumer->first_name : '', ['class' => 'form-control input-sm', 'id' => 'first_name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="last_name" class="col-sm-4 control-label">{{ trans('as.bookings.last_name') }}  {{ Form::required('last_name', with(new Consumer)) }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('last_name', (isset($booking)) ? $booking->consumer->last_name : '', ['class' => 'form-control input-sm', 'id' => 'last_name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 control-label">{{ trans('as.bookings.email') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('email', (isset($booking)) ? $booking->consumer->email : '', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 control-label">{{ trans('as.bookings.phone') }}  {{ Form::required('phone', with(new Consumer)) }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('phone',(isset($booking)) ? $booking->consumer->phone : '', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-4 control-label">{{ trans('as.bookings.address') }}  {{ Form::required('address', with(new Consumer)) }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('address',(isset($booking)) ? $booking->consumer->address : '', ['class' => 'form-control input-sm', 'id' => 'address']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <label for="is_requested_employee">{{ Form::checkbox('is_requested_employee', 1, (isset($bookingService)) ? $bookingService->is_requested_employee : false, ['id' => 'is_requested_employee']) }} {{ trans('as.bookings.own_customer') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- endrow -->
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo">
                <h4 class="panel-title" id="panel-add-service-handle">
                   2. {{ trans('as.bookings.add_service') }}
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="added_services" class="table table-bordered" style="@if(empty($bookingService))display:none @endif">
                                <thead>
                                    <tr>
                                        <th>{{ trans('as.bookings.service_employee') }}</th>
                                        <th>{{ trans('as.bookings.modify_time') }}</th>
                                        <th>{{ trans('as.bookings.plustime') }}</th>
                                        <th>{{ trans('as.bookings.date_time') }}</th>
                                        <th>{{ trans('as.bookings.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span id="added_service_name">
                                                @if (!empty($bookingService)) {{ $bookingService->service->name }}
                                                @endif
                                            </span>
                                            <br>
                                            <span id="added_employee_name">
                                                @if (!empty($bookingService)) {{ $booking->employee->name }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span id="added_booking_modify_time">
                                                @if (!empty($bookingService)) {{ $booking->modify_time }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span id="added_booking_plustime">
                                                @if (!empty($bookingService)) {{ $booking->plustime }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span id="added_booking_date">
                                                @if (!empty($bookingService)) {{ $booking->date }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align_right">
                                            <span id="added_service_price">
                                                @if (!empty($bookingService)) {{ $booking->total_price }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">{{ trans('as.bookings.categories') }}</label>
                            <div class="col-sm-8">
                                 {{ Form::select('service_categories', $categories, (!empty($category_id)) ? $category_id : -1, ['class' => 'form-control input-sm', 'id' => 'service_categories']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="services" class="col-sm-4 control-label">{{ trans('as.bookings.services') }}</label>
                            <div class="col-sm-8">
                                {{ Form::select('services', (isset($services)) ? $services : array(), (!empty($service_id)) ? $service_id : -1, ['class' => 'form-control input-sm', 'id' => 'services']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="service_times" class="col-sm-4 control-label">{{ trans('as.bookings.service_time') }} </label>
                            <div class="col-sm-8">
                            <select class="form-control input-sm" id="service_times" name="service_times">
                                @if(!empty($serviceTimes))
                                    @foreach ($serviceTimes as $serviceTime)
                                        <option
                                        @if (isset($serviceTime['length']))
                                            data-length="{{ (int) $serviceTime['length'] + $plustime}}"
                                        @endif
                                        @if (intval($bookingServiceTime) === intval($serviceTime['id']))
                                            selected="selected"
                                        @endif
                                            value="{{ $serviceTime['id']}}">{{ (int) ($serviceTime['name'] + $plustime) }}@if (isset($serviceTime['description']) && $serviceTime['description'])
                                                ({{ $serviceTime['description'] }})
                                            @endif</option>
                                    @endforeach
                                @endif
                             </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="modify_times" class="col-sm-4 control-label">{{ trans('as.bookings.modify_time') }} </label>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm spinner" data-inc="15">
                                    {{ Form::text('modify_times', isset($modifyTime) ? $modifyTime : 0, ['class' => 'form-control input-sm', 'id' => 'modify_times']) }}
                                    <div class="input-group-btn-vertical">
                                        <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                                        <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                @if(!empty($booking))
                                <button id="btn-add-service" class="btn btn-primary btn-sm pull-right">{{ trans('common.edit') }}</button>
                                @else
                                <button id="btn-add-service" class="btn btn-primary btn-sm pull-right">{{ trans('common.add') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="booking_date" class="col-sm-4 control-label">{{ trans('as.bookings.date') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('booking_date', isset($bookingDate) ? $bookingDate : '', ['class' => 'form-control input-sm', 'id' => 'booking_date', 'disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="employee_name" class="col-sm-4 control-label">{{ trans('as.bookings.employee') }}</label>
                                <div class="col-sm-8">
                                 {{ Form::text('employee_name', isset($employee) ? $employee->name : '', ['class' => 'form-control input-sm', 'id' => 'employee_name', 'disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="start_time" class="col-sm-4 control-label">{{ trans('as.bookings.start_at') }}</label>
                                <div class="col-sm-8">
                                   {{ Form::text('start_time', isset($startTime) ? $startTime : '', ['class' => 'form-control input-sm', 'id' => 'start_time', 'disabled'=>'disabled']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_time" class="col-sm-4 control-label">{{ trans('as.bookings.end_at') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('end_time', isset($endTime) ? $endTime : '', ['class' => 'form-control input-sm', 'id' => 'end_time', 'disabled'=>'disabled']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          @if(!empty($bookingExtraServices) && !$bookingExtraServices->isEmpty())
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3. {{ trans('as.bookings.extra_service') }}</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="extra_services" class="table table-bordered">
                             <thead>
                                    <tr>
                                        <th>{{ trans('as.services.extras.name') }}</th>
                                        <th>{{ trans('as.services.extras.length') }}</th>
                                        <th>{{ trans('as.services.extras.price') }}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingExtraServices as $bookingExtraService)
                                    <tr id="extra-service-{{ $bookingExtraService->extraService->id }}">
                                        <td>
                                            {{ $bookingExtraService->extraService->name }} {{ $bookingExtraService->extraService->description }}
                                        </td>
                                        <td>{{ $bookingExtraService->extraService->length }}</td>
                                        <td class="align_right"> {{ $bookingExtraService->extraService->price }}</td>
                                        <td>
                                           <a href="#" class="btn btn-default btn-remove-extra-service" data-remove-url="{{ route('as.bookings.remove-extra-service') }}" data-extra-service-id="{{ $bookingExtraService->extraService->id }}" data-booking-id="{{ $booking->id }}"><i class="glyphicon glyphicon-remove"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
         @endif
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12">
                    @if(empty($booking))
                    <input type="submit" id="btn-save-booking" class="btn btn-lg btn-success pull-right" value="{{ trans('common.save') }}" />
                    @else
                    <input type="submit" id="btn-save-booking" class="btn btn-lg btn-primary pull-right" value="{{ trans('common.edit') }}" />
                    @endif
                </div>
            </div>
        </div>
    </div>
@if(!empty($booking))
<input type="hidden" value="{{ $booking->id }}" name="booking_id">
<input type="hidden" value="{{ $booking->employee->id }}" name="employee_id" id="employee_id">
<input type="hidden" value="{{ route('as.bookings.add') }}" name="add_booking_url" id="add_booking_url">
<input type="hidden" id="add_service_url" value="{{ route('as.bookings.service.add') }}">
@endif
</form>
</div>
<input type="hidden" value="" id="consumer_data"/>
@if(!isset($upsert))
    @include ('modules.as.bookings.formScript')
@endif
