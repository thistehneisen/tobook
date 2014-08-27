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
                    <li class="{{ $employee->getSlotClass($hour, $minuteShift) }}">varaa</li>
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
@stop
