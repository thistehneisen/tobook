<!DOCTYPE html>
<html>
<head>
    <title>Commission Report</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
    * {
        font-family: 'DejaVu Serif';
        font-size: 14px;
    }
    </style>
</head>
<body>
<center>
    <h1>{{ $user->business->name }}</h1>
    <p>{{ Str::upper(trans(strtolower('common.' . $current->format('F')))); }} {{ $current->format('Y') }} </p>
</center>

@if($employeeBookings->count())
<center>
    <h2>{{ trans('admin.commissions.employees')}}</h2>
</center>

@include($langPrefix . '.' .'pdf-table', ['items' => $employeeBookings, 'fields'=> $fields, 'langPrefix' => $langPrefix])

@endif

@if(!empty($employeeId))
@foreach ($freelancersBookings as $name => $items)
<center>
    <h2>{{ $name }}</h2>
</center>
@include($langPrefix . '.' .'pdf-table', ['items' => $items, 'fields'=> $fields, 'langPrefix' => $langPrefix])
@endforeach
@endif

@if(App::environment() !== 'tobook' && Config::get('varaa.commission_style') !== 'tobook')
<center>
    <h2>
        {{ trans('admin.commissions.paid_this_month') }}:<br/>
        {{ number_format($paid, 2) }}{{ $currencySymbol }}
    </h2>
</center>
<center>
    <h2>
        {{ trans('admin.commissions.payment_pending') }}:<br/>
        {{ number_format($pending, 2) }}{{ $currencySymbol }}
    </h2>
</center>
@endif

@if(App::environment() === 'tobook' || Config::get('varaa.commission_style') === 'tobook')
    @include($langPrefix . '.' .'pdf-footer-tobook', [
        'steadyCommision'       => $steadyCommision,
        'paidDepositCommission' => $paidDepositCommission,
        'newConsumerCommission' => $newConsumerCommission
    ])
@endif
</body>
</html>