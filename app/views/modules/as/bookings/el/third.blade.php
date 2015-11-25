<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
             <a data-toggle="collapse" data-parent="#accordion" href="#collapseThird">3. {{ trans('as.bookings.confirmation_settings') }}</a>
        </h4>
    </div>
    <div id="collapseThird" class="panel-collapse collapse">
         <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="confirmation_sms" class="col-sm-6 control-label">{{ trans('as.bookings.confirmation_sms') }}</label>
                        <div class="col-sm-6">
                            <div class="radio-group">
                                <label>
                                    {{ Form::radio('is_confirmation_sms', 1, false) }}
                                    {{ trans('common.yes') }}
                                </label>
                                &nbsp;
                                <label>
                                    {{ Form::radio('is_confirmation_sms', 0, false) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirmation_email" class="col-sm-6 control-label">{{ trans('as.bookings.confirmation_email') }}</label>
                          <div class="col-sm-6">
                            <div class="radio-group">
                                <label>
                                    {{ Form::radio('is_confirmation_email', 1, false) }}
                                    {{ trans('common.yes') }}
                                </label>
                                &nbsp;
                                <label>
                                    {{ Form::radio('is_confirmation_email', 0, false) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="reminder_sms" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_sms') }}</label>
                        <div class="col-sm-6">
                            <div class="radio-group">
                                <label>
                                    {{ Form::radio('is_reminder_sms', 1, false) }}
                                    {{ trans('common.yes') }}
                                </label>
                                &nbsp;
                                <label>
                                    {{ Form::radio('is_reminder_sms', 0, false) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_sms_at" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_sms_at') }}</label>
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm spinner" data-inc="1">
                                 {{ Form::text('reminder_sms_at', '', ['class' => 'form-control input-sm', 'id' => 'reminder_sms_at', 'data-positive' => 'true']) }}
                                 <div class="input-group-btn-vertical">
                                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_sms_time_unit" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_sms_time_unit') }}</label>
                        <div class="col-sm-6">
                            {{ Form::select('reminder_sms_time_unit', [1 => trans('common.hour'), 2 => trans('common.day')], !(empty($booking->reminder_sms_time_unit)) ?  $booking->reminder_sms_time_unit : $user->asOptions['reminder_sms_time_unit'], ['class'=> 'form-control input-sm', 'id' => 'reminder_sms_time_unit']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_email" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_email') }}</label>
                        <div class="col-sm-6">
                            <div class="radio-group">
                                <label>
                                    {{ Form::radio('is_reminder_email', 1, false) }}
                                    {{ trans('common.yes') }}
                                </label>
                                &nbsp;
                                <label>
                                    {{ Form::radio('is_reminder_email', 0, false) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_sms_at" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_email_at') }}</label>
                        <div class="col-sm-6">
                            <div class="input-group input-group-sm spinner" data-inc="1">
                                 {{ Form::text('reminder_email_at', '', ['class' => 'form-control input-sm spinner', 'id' => 'reminder_email_at', 'data-positive' => 'true']) }}
                                 <div class="input-group-btn-vertical">
                                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_email_time_unit" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_email_time_unit') }}</label>
                        <div class="col-sm-6">
                            {{ Form::select('reminder_email_time_unit', [1 => trans('common.hour'), 2 => trans('common.day')], 0 , ['class'=> 'form-control input-sm', 'id' => 'reminder_email_time_unit']) }}
                        </div>
                    </div>
                    <!---end-->
                </div>
            </div>
        </div>
    </div>
</div>