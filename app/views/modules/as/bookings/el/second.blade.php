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
                    <table id="added_services" class="table table-bordered" style="@if(empty($bookingServices))display:none @endif" data-edit-text="{{ trans('common.edit') }}" data-add-text="{{ trans('common.add') }}">
                        <thead>
                            <tr>
                                <th>{{ trans('as.bookings.service_employee') }}</th>
                                <th>{{ trans('as.bookings.plustime') }}</th>
                                <th>{{ trans('as.bookings.service_time') }}</th>
                                <th>{{ trans('as.bookings.date_time') }}</th>
                                <th>{{ trans('as.bookings.price') }}</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingServices as $bookingService)
                            <tr id="booking-service-id-{{ $bookingService->id }}" data-booking-service-id="{{ $bookingService->id }}">
                                <td>
                                    <span class="added_service_name">
                                        @if (!empty($bookingService)) {{ $bookingService->service->name }}
                                        @endif
                                    </span>
                                    <br>
                                    <span class="added_employee_name">
                                        @if (!empty($bookingService)) {{ $booking->employee->name }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="added_booking_plustime">
                                        @if (!empty($bookingService)) {{ $bookingService->getEmployeePlustime() }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="added_booking_service_length">
                                        @if (!empty($bookingService)) {{ $bookingService->selectedService->length }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="added_booking_date">
                                        @if (!empty($bookingService)) {{ str_standard_to_local($booking->date) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="align_right">
                                    <span class="added_service_price">
                                        @if (!empty($bookingService)) {{ $bookingService->calculcateTotalPrice() }}
                                        @endif
                                    </span>
                                </td>
                                 <td class="align_right">
                                    <a href="#" class="btn-delete-booking-service" data-booking-service-id="{{ $bookingService->id }}" data-booking-id="{{ $bookingService->booking_id }}" data-start-time="{{ $booking->startTime->format('H:i') }}" data-uuid="{{ $bookingService->tmp_uuid }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
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
                        <button id="btn-add-service" class="btn btn-primary btn-sm pull-right">{{ trans('common.add') }}</button>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="extra_services" class="col-sm-4 control-label">{{ trans('as.bookings.extra_service') }}</label>
                    <div class="col-sm-8">
                        {{ Form::select('extra_services[]', $extras, 0 , array('class'=> 'selectpicker form-control input-sm', 'id' => 'extra_services', 'multiple' => true, 'title' => trans('as.nothing_selected'))) }}
                    </div>
                </div>
            </div>
             <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="booking_date" class="col-sm-4 control-label">{{ trans('as.bookings.date') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('booking_date', isset($bookingDate) ? str_standard_to_local($bookingDate) : '', ['class' => 'form-control input-sm', 'id' => 'booking_date', 'disabled'=>'disabled']) }}
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
                        <label for="total_length" class="col-sm-4 control-label">{{ trans('as.bookings.total_length') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('total_length', isset($totalLength) ? $totalLength : '', ['class' => 'form-control input-sm', 'id' => 'total_length', 'disabled'=>'disabled']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="total_price" class="col-sm-4 control-label">{{ trans('as.bookings.total_price') }}</label>
                        <div class="col-sm-8 @if($couponApplied) coupon-price @endif">
                            {{ Form::text('total_price', isset($totalPrice) ? $totalPrice : '', ['class' => 'form-control input-sm', 'id' => 'total_price', 'disabled'=>'disabled']) }}
                        </div>
                    </div>
                    @if(Settings::get('deposit_payment'))
                    <div class="form-group row">
                        <label for="deposit" class="col-sm-4 control-label">{{ trans('as.bookings.deposit') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('total_price', isset($booking) ? $booking->deposit : '0', ['class' => 'form-control input-sm', 'id' => 'total_price', 'disabled'=>'disabled']) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>          