@extends ('modules.as.layout')
@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style(asset_path('as/styles/main.css')) }}
@stop

@section ('scripts')
{{ HTML::script(asset_path('core/scripts/jquery.fixedTableHeader.js')) }}
<script type="text/javascript">
    $(window).load(function () {
        $('#workshift-summary').fixedTableHeader();

        $('.workshift-editable').click(function(){
            var $this = $(this);
            var custom_time = {{ $customTimes }};
            if($this.data('editable') === true) {
                $this.data('editable', false);
                var dropdown = $('<select/>', {
                    class: 'form-control',
                    style: 'max-width:70%; display:inline'
                });
                for(var val in custom_time) {
                    $('<option />', {
                        value: val,
                        text: custom_time[val]
                    }).appendTo(dropdown);
                }
                var btnOk = $('<input/>', {
                    value: 'OK',
                    type: 'button',
                    class: 'btn btn-primary btn-change-workshift',
                });
                $this.empty();
                dropdown.appendTo($this);
                btnOk.appendTo($this);
            }
        });

        $(document).on('click', '.btn-change-workshift', function(e) {
            e.preventDefault();
            var $this = $(this);
            var parentSpan = $this.closest('span');
            var workshiftSelect  = $this.prev('select');
            var custom_time_id   = workshiftSelect.val();
            var custom_time_text = workshiftSelect.find("option:selected").text()
            var url = $('#update_workshift_url').val();
            $.post(url, {
                'custom_time_id': custom_time_id,
                'employee_id'   : parentSpan.data('employee-id'),
                'date'          : parentSpan.data('date'),
            }).done(function(data){
                parentSpan.data('editable', true);
                parentSpan.empty();
                parentSpan.text(custom_time_text);
            });
        });
    });
</script>
@stop

@section ('title')
    {{ trans('as.employees.custom_time') }}
@stop

@section ('content')
 <div class="form-group row">
        <div class="col-sm-3 hidden-print"><a href="{{ route('as.employees.employeeCustomTime.summary', ['date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3 hidden-print">
           {{ Str::upper(trans(strtolower('common.' . $current->format('F')))); }}
        </div>
        <div class="col-sm-3 hidden-print"><a href="{{ route('as.employees.employeeCustomTime.summary', ['date'=> with(clone $current->startOfMonth())->addMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.next')) }}</a></div>
        <div class="col-sm-3 hidden-print">
             <button class="btn btn-primary pull-right" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
        </div>
</div>
<table id="workshift-summary" class="table table-striped table-bordered">
    <thead>
        <th>{{ trans('as.employees.weekday')}}</th>
        <th>{{ trans('as.employees.date')}}</th>
        @foreach ($employees as $employee)
        <th>{{ $employee->name }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach($currentMonths as $item)
             <tr @if($item['date']->dayOfWeek === \Carbon\Carbon::SUNDAY) class="sunday-row" @endif>
                <td>{{ trans(strtolower('common.' . $item['date']->format('l'))) }}</td>
                <td>{{ $item['date']->toDateString() }}</td>
                @foreach ($employees as $employee)
                <td>
                    @if (!empty($item['employees'][$employee->id]->customTime))
                    <span class="workshift-editable" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">{{ $item['employees'][$employee->id]->customTime->name }}</span>
                    @else
                    <span class="workshift-editable" data-editable="true" data-employee-id="{{$employee->id}}" data-date="{{$item['date']->toDateString()}}">--</span>
                    @endif
                </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>{{ trans('as.employees.saturday_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($sarturdayHours[$employee->id])) {{ $sarturdayHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <td>{{ trans('as.employees.sunday_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($sundayHours[$employee->id])) {{ $sundayHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            <td>{{ trans('as.employees.monthly_hours') }}</td>
            <td>&nbsp;</td>
            @foreach ($employees as $employee)
            <td>
                @if (isset($montlyHours[$employee->id])) {{ $montlyHours[$employee->id] }}
                @else
                --
                @endif
            </td>
            @endforeach
        </tr>
    </tfoot>
</table>
<input type="hidden" id="update_workshift_url" value="{{ route('as.employees.employeeCustomTime.updateWorkshift') }}"/>
@stop
