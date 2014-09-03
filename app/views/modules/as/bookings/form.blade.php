<div id="book-form" class="as-calendar-book">
<h2>Lisää Varaus</h2>
<form id="booking_form">
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1. Booking info</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group row">
                                <label for="booking_uuid" class="col-sm-4 control-label">Booking ID</label>
                                <div class="col-sm-8">
                                    {{ Form::text('booking_uuid', $uuid , ['class' => 'form-control input-sm', 'id' => 'booking_uuid']) }}
                                    <input type="hidden" name="uuid" id="uuid" value="{{ $uuid }}"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="booking_status" class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ Form::select('booking_status', $bookingStatuses, 'confirmed', ['class' => 'form-control input-sm', 'id' => 'booking_status']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-sm-4 control-label">Notes</label>
                                <div class="col-sm-8">
                                    {{ Form::textarea('notes', '', ['class' => 'form-control input-sm', 'id' => 'notes']) }}
                                </div>
                            </div>
                        </div>
           <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="consumer_firstname" class="col-sm-4 control-label">Firstname</label>
                                <div class="col-sm-8">
                                    {{ Form::text('consumer_firstname', '', ['class' => 'form-control input-sm', 'id' => 'consumer_firstname']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="consumer_lastname" class="col-sm-4 control-label">Lastname</label>
                                <div class="col-sm-8">
                                    {{ Form::text('consumer_lastname', '', ['class' => 'form-control input-sm', 'id' => 'consumer_lastname']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="consumer_email" class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    {{ Form::text('consumer_email', '', ['class' => 'form-control input-sm', 'id' => 'consumer_email']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="consumer_phone" class="col-sm-4 control-label">Phone</label>
                                <div class="col-sm-8">
                                    {{ Form::text('consumer_phone','', ['class' => 'form-control input-sm', 'id' => 'consumer_phone']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="consumer_address" class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8">
                                    {{ Form::text('consumer_address','', ['class' => 'form-control input-sm', 'id' => 'consumer_address']) }}
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2. Add service</a>
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
                                        <th>Service/Employee</th>
                                        <th>Date Time</th>
                                        <th>Price</th>
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
                                           <a href="#" id="btn-remove-service-time" class="btn btn-default" data-uuid="{{ $uuid }}"><i class="glyphicon glyphicon-remove"></i></a>
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
                            <label for="name" class="col-sm-4 control-label">Categories</label>
                            <div class="col-sm-8">
                                 {{ Form::select('service_categories', $categories, 0, ['class' => 'form-control input-sm', 'id' => 'service_categories']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="services" class="col-sm-4 control-label">Service</label>
                            <div class="col-sm-8">
                                {{ Form::select('services', array(), 0, ['class' => 'form-control input-sm', 'id' => 'services']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="service_times" class="col-sm-4 control-label">Service Time</label>
                            <div class="col-sm-8">
                               {{ Form::select('service_times', array(), 0, ['class' => 'form-control input-sm', 'id' => 'service_times']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="modify_times" class="col-sm-4 control-label">Modify Time</label>
                            <div class="col-sm-8">
                                {{ Form::select('modify_times', array_combine(range(-60,60, 15), range(-60,60, 15)), 4, ['class' => 'form-control input-sm', 'id' => 'modify_times']) }}
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
                                <label for="booking_date" class="col-sm-4 control-label">Date</label>
                                <div class="col-sm-8">
                                    {{ Form::text('booking_date', isset($bookingDate) ? $bookingDate : '', ['class' => 'form-control input-sm', 'id' => 'booking_date']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="employee_name" class="col-sm-4 control-label">Employee</label>
                                <div class="col-sm-8">
                                 {{ Form::text('employee_name', isset($employee) ? $employee->name : '', ['class' => 'form-control input-sm', 'id' => 'employee_name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="start_time" class="col-sm-4 control-label">Start time</label>
                                <div class="col-sm-8">
                                   {{ Form::text('start_time', isset($startTime) ? $startTime : '', ['class' => 'form-control input-sm', 'id' => 'start_time']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="end_time" class="col-sm-4 control-label">End time</label>
                                <div class="col-sm-8">
                                    {{ Form::text('end_time', '12:00', ['class' => 'form-control input-sm', 'id' => 'end_time']) }}
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
