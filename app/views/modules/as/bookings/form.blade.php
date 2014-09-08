<div id="book-form" class="as-calendar-book">
<h2>Lisää Varaus</h2>
<form id="booking_form">
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1. {{ trans('as.bookings.booking_info') }}</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group row">
                                <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('as.bookings.booking_id') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('booking_uuid', $uuid , ['class' => 'form-control input-sm', 'id' => 'booking_uuid']) }}
                                    <input type="hidden" name="uuid" id="uuid" value="{{ $uuid }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="booking_status" class="col-sm-4 control-label">{{ trans('as.bookings.status') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::select('booking_status', $bookingStatuses, 'confirmed', ['class' => 'form-control input-sm', 'id' => 'booking_status']) }}
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
                            <div class="form-group row">
                                <div class="col-sm-12">
                                 <a href="#book-form" id="btn-continute-action" class="btn btn-primary btn-sm pull-right">{{ trans('common.search') }}</a>
                             </div>
                         </div>
                        </div>
                    </div>
                    <!-- endrow -->
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2. {{ trans('as.bookings.add_service') }}</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="added_services" class="table table-bordered" style="display:none">
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
                                            <span id="added_service_name"></span><br>
                                            <span id="added_employee_name"></span>
                                        </td>
                                        <td> <span id="added_booking_date"></span></td>
                                        <td class="align_right"> <span id="added_service_price"></span></td>
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
                               {{ Form::select('service_times', (isset($serviceTimes)) ? $serviceTimes : array(), isset($selectedServiceTime) ? $selectedServiceTime : '', ['class' => 'form-control input-sm', 'id' => 'service_times']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="modify_times" class="col-sm-4 control-label">{{ trans('as.bookings.modify_time') }} </label>
                            <div class="col-sm-8">
                                {{ Form::select('modify_times', array_combine(range(-60,60, 15), range(-60,60, 15)), isset($modifyTime) ? $modifyTime : '', ['class' => 'form-control input-sm', 'id' => 'modify_times']) }}
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
                                    {{ Form::text('booking_date', isset($bookingDate) ? $bookingDate : '', ['class' => 'form-control input-sm', 'id' => 'booking_date']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="employee_name" class="col-sm-4 control-label">{{ trans('as.bookings.employee') }}</label>
                                <div class="col-sm-8">
                                 {{ Form::text('employee_name', isset($employee) ? $employee->name : '', ['class' => 'form-control input-sm', 'id' => 'employee_name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="start_time" class="col-sm-4 control-label">{{ trans('as.bookings.start_time') }}</label>
                                <div class="col-sm-8">
                                   {{ Form::text('start_time', isset($startTime) ? $startTime : '', ['class' => 'form-control input-sm', 'id' => 'start_time']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_time" class="col-sm-4 control-label">{{ trans('as.bookings.end_time') }}</label>
                                <div class="col-sm-8">
                                    {{ Form::text('end_time', isset($endTime) ? $endTime : '', ['class' => 'form-control input-sm', 'id' => 'end_time']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12">
                    <a href="#book-form" id="btn-save-booking" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
