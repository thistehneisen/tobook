<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Settings</a>
        </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
         <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="confirmation_sms" class="col-sm-6 control-label">{{ trans('as.bookings.confirmation_sms') }}</label>
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.yes') }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirmation_email" class="col-sm-6 control-label">{{ trans('as.bookings.confirmation_email') }}</label>
                          <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.yes') }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
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
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.yes') }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reminder_email" class="col-sm-6 control-label">{{ trans('as.bookings.reminder_email') }}</label>
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.yes') }}
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('reminder_email', false, ['class' => 'form-control input-sm']) }}
                                    {{ trans('common.no') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>