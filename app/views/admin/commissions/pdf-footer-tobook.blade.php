<center>
    <h2>{{ trans('admin.commissions.commission_calculation')}}</h2>
</center>
<table style="width: 100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
        <td colspan="4"><b>{{ trans('admin.commissions.payment_for_online_order')}}</b></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ trans('admin.commissions.number_of_orders')}}</td>
        <td>{{ trans('admin.commissions.commission')}}</td>
        <td>{{ trans('admin.commissions.commission_total')}}</td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $steadyCommision->total }}</td>
        <td>{{ Settings::get('constant_commission') }}</td>
        <td>{{ $steadyCommision->commision_total }}</td>
    </tr>
     <tr>
        <td colspan="4"><b>{{ trans('admin.commissions.payment_for_money_transfer')}}</b></td>
    </tr>
    <tr>
        <td></td>
        <td>{{ trans('admin.commissions.sum')}}</td>
        <td>{{ trans('admin.commissions.percentage')}}</td>
        <td>{{ trans('admin.commissions.total')}}</td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $paidDepositCommission->total }}</td>
        <td>{{ Settings::get('commission_rate') }}</td>
        <td>{{ $paidDepositCommission->commision_total }}</td>
    </tr>
    <tr>
        <td colspan="4"><b>{{ trans('admin.commissions.payment_for_new_consumers')}}</b></td>
    </tr>
    <tr>
        <td>{{ trans('admin.commissions.number_of_consumers')}}</td>
        <td>{{ trans('admin.commissions.sum')}}</td>
        <td>{{ trans('admin.commissions.percentage')}}</td>
        <td>{{ trans('admin.commissions.total')}}</td>
    </tr>
    <tr>
        <td></td>
        <td>{{ $newConsumerCommission->total }}</td>
        <td>{{ Settings::get('new_consumer_commission_rate') }}</td>
        <td>{{ $newConsumerCommission->commision_total }}</td>
    </tr>
    <tr>
        <td colspan="2">{{ sprintf(trans('admin.commissions.domain_commission_total'), 'ToBook')}}</td>
        <td colspan="2">0</td>
    </tr>
    <tr>
        <td colspan="2">{{ sprintf(trans('admin.commissions.domain_charged_from_visitor'), 'ToBook')}}</td>
        <td colspan="2">0</td>
    </tr>
    <tr>
        <td colspan="2">{{ sprintf(trans('admin.commissions.domain_amount_transfered_to_cusomter'), 'ToBook')}}</td>
        <td colspan="2">0</td>
    </tr>
</table>
