<div class="container as-date-nav">
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" data-index-url="{{ route($routeName, ['id' => $employeeId]) }}" id="calendar_date" class="form-control" value="{{ with(new Carbon\Carbon($selectedDate))->format('d-m-Y') }}">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8">
        <a href="{{ route($routeName, ['date'=> Carbon\Carbon::today()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default">{{ trans('as.index.today') }}</a>
        <a href="{{ route($routeName, ['date'=> Carbon\Carbon::tomorrow()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default">{{ trans('as.index.tomorrow') }}</a>

        <div class="btn-group">
            <a href="{{ route($routeName, ['date'=> with(clone $date)->subWeek()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link"><i class="fa fa-fast-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->subDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link"><i class="fa fa-backward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link"><i class="fa fa-forward"></i></a>
            <a href="{{ route($routeName, ['date'=> with(clone $date)->addWeek()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-link"><i class="fa fa-fast-forward"></i></a>
        </div>

        <div class="btn-group">
            <?php
                $startOfWeek = with(clone $date)->startOfWeek();
                $endOfWeek = with(clone $date)->endOfWeek();
            ?>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::MONDAY) btn-primary @endif">{{ trans('common.short.mon') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::TUESDAY) btn-primary @endif">{{ trans('common.short.tue') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::WEDNESDAY) btn-primary @endif">{{ trans('common.short.wed') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::THURSDAY) btn-primary @endif">{{ trans('common.short.thu') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::FRIDAY) btn-primary @endif">{{ trans('common.short.fri') }}</a>
            <a href="{{ route($routeName, ['date'=> $startOfWeek->addDay()->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SATURDAY) btn-primary @endif">{{ trans('common.short.sat') }}</a>
            <a href="{{ route($routeName, ['date'=> $endOfWeek->toDateString(), 'id'=> $employeeId]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SUNDAY) btn-primary @endif">{{ trans('common.short.sun') }}</a>
        </div>
    </div>

    <div class="col-md-2 text-right">
        <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
    </div>
</div>
