<div id="book-form" class="@if(isset($upsert)) as-edit-booking @else as-calendar-book @endif">
@if(empty($booking))
<h2>{{ trans('as.bookings.add') }}</h2>
@else
<h2>{{ trans('as.bookings.edit') }}</h2>
@endif
<form id="booking_form">
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
                <h4 class="panel-title">
                    1. {{ trans('as.bookings.booking_info') }}
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group row">
                                <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('as.bookings.booking_id') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('uuid', $uuid , ['class' => 'form-control input-sm', 'id' => 'uuid', 'disabled'=> 'disabled']) }}
                                    <input type="hidden" name="booking_uuid" id="booking_uuid" value="{{ $uuid }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="booking_status" class="col-sm-4 control-label">{{ trans('as.bookings.status') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::select('booking_status', $bookingStatuses, (isset($booking)) ? $booking->getStatusText() : '', ['class' => 'form-control input-sm', 'id' => 'booking_status']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-sm-4 control-label">{{ trans('as.bookings.notes') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::textarea('notes', (isset($booking)) ? $booking->notes : '', ['class' => 'form-control input-sm', 'id' => 'notes']) }}
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
                                <label for="consumer_firstname" class="col-sm-4 control-label">{{ trans('as.bookings.firstname') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('firstname', (isset($booking)) ? $booking->consumer->first_name : '', ['class' => 'form-control input-sm', 'id' => 'firstname']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lastname" class="col-sm-4 control-label">{{ trans('as.bookings.lastname') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('lastname', (isset($booking)) ? $booking->consumer->last_name : '', ['class' => 'form-control input-sm', 'id' => 'lastname']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 control-label">{{ trans('as.bookings.email') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('email', (isset($booking)) ? $booking->consumer->email : '', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="phone" class="col-sm-4 control-label">{{ trans('as.bookings.phone') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('phone',(isset($booking)) ? $booking->consumer->phone : '', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-4 control-label">{{ trans('as.bookings.address') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('address',(isset($booking)) ? $booking->consumer->address : '', ['class' => 'form-control input-sm', 'id' => 'address']) }}
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
                <h4 class="panel-title">
                   2. {{ trans('as.bookings.add_service') }}
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="added_services" class="table table-bordered" style="@if(empty($bookingService))display:none @endif">
                                <thead>
                                    <tr>
                                        <th>{{ trans('as.bookings.service_employee') }}</th>
                                        <th>{{ trans('as.bookings.date_time') }}</th>
                                        <th>{{ trans('as.bookings.price') }}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span id="added_service_name">
                                                @if(!empty($bookingService))
                                                    {{ $bookingService->service->name }}
                                                @endif
                                            </span>
                                            <br>
                                            <span id="added_employee_name">
                                                @if(!empty($bookingService))
                                                    {{ $booking->employee->name }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span id="added_booking_date">
                                                @if(!empty($bookingService))
                                                    {{ $booking->date }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align_right">
                                            <span id="added_service_price">
                                                @if(!empty($bookingService))
                                                    {{ $booking->total_price }}
                                                @endif
                                            </span>
                                            </td>
                                        <td>
                                           <a href="#" id="btn-remove-service-time" class="btn btn-default" data-remove-url="{{ route('as.bookings.service.remove') }}" data-uuid="{{ $uuid }}"><i class="glyphicon glyphicon-remove"></i></a>
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
                                            data-length="{{ $serviceTime['length'] }}"
                                        @endif
                                        @if ($bookingServiceTime == (int)$serviceTime['id'])
                                            selected="selected"
                                        @endif
                                            value="{{ $serviceTime['id']}}">
                                                {{ $serviceTime['name'] }}
                                            @if (isset($serviceTime['description']) && $serviceTime['description'])
                                                ({{ $serviceTime['description'] }})
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                             </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="modify_times" class="col-sm-4 control-label">{{ trans('as.bookings.modify_time') }} </label>
                            <div class="col-sm-8">
                                {{ Form::select('modify_times', array_combine(range(-60,60, 15), range(-60,60, 15)), isset($modifyTime) ? $modifyTime : 0, ['class' => 'form-control input-sm', 'id' => 'modify_times']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button id="btn-add-service" class="btn btn-primary btn-sm pull-right">{{ trans('common.add') }}</button>
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
                    <div class="clearfix">&nbsp;</div>
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
                    <a href="#book-form" id="btn-save-booking" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
                    @else
                    <a href="#book-form" id="btn-save-booking" class="btn btn-primary btn-sm pull-right">{{ trans('common.edit') }}</a>
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

