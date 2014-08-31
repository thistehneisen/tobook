@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>Etusivu</strong></p>
    <p>Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.</p>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" class="form-control date-picker">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8">
        <button type="button" class="btn btn-default">Tänään</button>
        <button type="button" class="btn btn-default">Huomenna</button>

        <div class="btn-group">
            <button class="btn btn-link"><i class="fa fa-fast-backward"></i></button>
            <button class="btn btn-link"><i class="fa fa-backward"></i></button>
            <button class="btn btn-link"><i class="fa fa-forward"></i></button>
            <button class="btn btn-link"><i class="fa fa-fast-forward"></i></button>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-default">Ma</button>
            <button type="button" class="btn btn-default">Ti</button>
            <button type="button" class="btn btn-default">Ke</button>
            <button type="button" class="btn btn-default btn-primary">To</button>
            <button type="button" class="btn btn-default">Pe</button>
            <button type="button" class="btn btn-default">La</button>
            <button type="button" class="btn btn-default">Su</button>
        </div>
    </div>

    <div class="col-md-2 text-right">
        <button class="btn btn-primary"><i class="fa fa-print"> Tulosta</i></button>
    </div>
</div>

<div class="row row-no-padding">
    <h3 class="comfortaa">Good to know</h3>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <ul class="as-col-left-header">
            <li class="as-col-header">&nbsp;</li>
            @foreach ($workingTimes as $hour)
                @foreach (range(0, 45, 15) as $minuteShift)
                    <li>{{ sprintf('%02d', $hour) }} : {{ sprintf('%02d', $minuteShift) }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
    <div class="as-calendar as-table-wrapper col-lg-11 col-md-11 col-sm-11 col-xs-11">
        @foreach ($employees as $employee)
        <div class="as-col">
            <ul>
                <li class="as-col-header">{{ $employee->name }}</li>
                @foreach ($workingTimes as $hour)
                     @foreach (range(0, 45, 15) as $minuteShift)
                    <li data-employee-id="{{ $employee->id }}" data-time="{{ sprintf('%02d:%02d', $hour, $minuteShift) }}" href="#select-action" class="fancybox {{ $employee->getSlotClass($hour, $minuteShift) }}">varaa</li>
                     @endforeach
                @endforeach
            </ul>
       </div>
       @endforeach
       <div class="as-col">
            <ul>
                <li class="as-col-header">Employee 1</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li>varaa</li>
                <li class="booked">
                    <span class="customer-tooltip"title="Cao Luu Bao An This is a very long text heheh">Cao Luu Bao An This is a very long text heheh (Service 3)</span>
                    <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                </li>
                <li class="booked"></li>
                <li class="booked"></li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
            </ul>
       </div>
    </div>
</div>
<div id="select-action" class="as-modal-form as-calendar-action">
<h2>Kalenteri</h2>
<table class="table table-condensed">
    <tbody>
        <tr>
            <td><input type="radio" id="freetime" value="freetime" name="action_type"></td>
            <td><label for="freetime">Lisää vapaa</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="book" value="book" name="action_type"></td>
            <td><label for="book">Tee varaus</label></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="as-submit-row" colspan="2">
            <a href="#" id="btn-continute-action" class="btn btn-primary">{{ trans('common.continue') }}</a>
            <a onclick="$.fancybox.close();" id="btn-cancel-action" class="btn btn-danger">{{ trans('common.cancel') }}</a>
        </td>
    </tr>
    </tfoot>
</table>
</div>
<div id="book-form" class="as-modal-form as-calendar-book">
<h2>Lisää Varaus</h2>
<form>
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
                                <label for="name" class="col-sm-4 control-label">Booking ID</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Notes</label>
                                <div class="col-sm-8">
                                    {{ Form::textarea('name', '', ['class' => 'form-control input-sm', 'id' => 'notes']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Date</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Employee</label>
                                <div class="col-sm-8">
                                    <span class="form-control">Employee 2</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Start time</label>
                                <div class="col-sm-8">
                                    <span class="form-control">8:00</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">End time</label>
                                <div class="col-sm-8">
                                    <span class="form-control">12:00</span>
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">2. Select Customer</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="row">
                           <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Email</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Phone</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 control-label">Address</label>
                                <div class="col-sm-8">
                                    {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                 <a href="#book-form" id="btn-continute-action" class="btn btn-primary btn-sm pull-right">{{ trans('common.search') }}</a>
                             </div>
                         </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">3. Add service</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Service/Employee</th>
                                        <th class="w110">Date Time</th>
                                        <th class="w90 align_right">Price</th>
                                        <th class="w90">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Service 1<br>
                                            Employee 2
                                        </td>
                                        <td>29/8/2014, 08:30</td>
                                        <td class="align_right">€10.00</td>
                                        <td><i class="glyphicon glyphicon-trash"></i></td>
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
                                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Service</label>
                            <div class="col-sm-8">
                                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Service Time</label>
                            <div class="col-sm-8">
                                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 control-label">Modify Time</label>
                            <div class="col-sm-8">
                                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <a href="#book-form" id="btn-continute-action" class="btn btn-primary btn-sm pull-right">{{ trans('common.add') }}</a>
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
                    <a href="#book-form" id="btn-continute-action" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@stop
