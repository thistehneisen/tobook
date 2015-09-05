<table class="body-wrap">
<tr>
    <td></td>
    <td class="container" width="600">
        <div class="content">
            <table class="main" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="content-wrap aligncenter">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="content-block">
                                    <h1 class="aligncenter">{{ show_money($transaction->amount) }} @lang('as.embed.receipt.paid')</h1>
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block">
                                    <h2 class="aligncenter">@lang('as.embed.receipt.thanks')</h2>
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block aligncenter">
                                    <table class="invoice">
                                        <tr>
                                            <td>{{ $consumer->name }}<br>@lang('as.embed.receipt.invoice'){{ $booking->uuid }}<br>{{ $booking->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            {{ $bookingService->service->name }} <br>
                                                            {{ $bookingService->employee->name }}, {{ $booking->date }} ({{ $bookingService->start_at }}) <br>
                                                            {{ $business->name }} <br>
                                                            {{ $business->full_address }} <br>
                                                        </td>
                                                        <td class="alignright">{{ show_money($bookingService->service->price) }}</td>
                                                    </tr>
                                                    <tr class="total" style="border-top: none !important; margin-top: 20px;">
                                                        <td class="alignright" style="border-top: none !important;">
                                                            @lang('as.embed.receipt.vat')
                                                        </td>
                                                        <td class="alignright" style="border-top: none !important;">
                                                            {{ show_money($vat) }}
                                                        </td>
                                                    </tr>
                                                    <tr class="total">
                                                        <td class="alignright" style="border-top: none !important;">@lang('as.embed.receipt.total')</td>
                                                        <td class="alignright" style="border-top: none !important;">{{ show_money($transaction->amount) }}</td>
                                                    </tr>

                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                            </tr>
                            <tr>
                                <td class="content-block aligncenter">
                                    @lang('as.embed.receipt.company')
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td class="aligncenter content-block">@lang('as.embed.receipt.questions') <a href="mailto:yritys@varaa.com">yritys@varaa.com</a></a></td>
                </tr>
            </table>
        </div>
    </td>
    <td></td>
</tr>
</table>
