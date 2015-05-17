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

@foreach ($freelancersBookings as $name => $items)
<center>
    <h2>{{ $name }}</h2>
</center>
@include($langPrefix . '.' .'pdf-table', ['items' => $items, 'fields'=> $fields, 'langPrefix' => $langPrefix])
@endforeach

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
