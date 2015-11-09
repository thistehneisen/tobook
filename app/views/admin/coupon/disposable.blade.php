@extends ('layouts.admin')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/graphael/0.5.1/g.raphael-min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/graphael/0.5.1/g.pie-min.js') }}
    <script>
$(function () {
	var r = Raphael("used-chart");
	pie = r.piechart(320, 125, 100, {{ $campaign->getPieChartData() }}, { legend: ["%%.%% - Not used", "%%.%% - Used"], legendpos: "west", href: ["http://raphaeljs.com", "http://g.raphaeljs.com"]});

    r.text(320, 10, "{{ trans('admin.coupon.campaign.bar_chart') }}").attr({ font: "20px sans-serif" });
    pie.hover(function () {
        this.sector.stop();
        this.sector.scale(1.1, 1.1, this.cx, this.cy);
 
        if (this.label) {
            this.label[0].stop();
            this.label[0].attr({ r: 7.5 });
            this.label[1].attr({ "font-weight": 800 });
        }
    }, function () {
        this.sector.animate({ transform: 's1 1 ' + this.cx + ' ' + this.cy }, 500, "bounce");

        if (this.label) {
            this.label[0].animate({ r: 5 }, 500, "bounce");
            this.label[1].attr({ "font-weight": 400 });
        }
    });

	new Morris.Bar({
	  // ID of the element in which to draw the chart.
	  element: 'date-used-chart',
	  // Chart data records -- each entry in this array corresponds to a point on
	  // the chart.
	  data: {{ $campaign->getBarChartData() }},
	  // The name of the data record attribute that contains x-values.
	  xkey: 'date',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['used'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: [
	    '{{ trans('admin.coupon.date') }}',
	    '{{ trans('admin.coupon.used') }}',
	  ],
	  // turn all x labels some degrees to show all labels
	  xLabelAngle: 30,
	  // hide the hover box on mouse out
	  hideHover: true
	});
	});
    </script>
@stop

@section('content')
    @include ('admin.coupon.tabs')

 	 <h4 class="comfortaa">{{ trans('admin.coupon.campaign.edit')}}</h4>
 	 <div class="row">
        <div class="col-md-6">
 	 	    <div class="col-md-6">
 	 		<ul>
 	 			<li>{{ $campaign->name }}</li>
 	 			<li>{{ $campaign->expireAt->toDateString(); }}</li>
 	 		</ul> 
            </div>
            <div class="col-md-6">
            {{ Form::open(['route' => ['admin.coupon.campaigns.export', $id], 'role' => 'form']) }}
                <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-file-excel-o "> {{ trans('common.export_csv')}}</i></button>
            {{ Form::close() }}
            </div>
 	 	</div>
 	 </div>

 	 <div class="row">
 	 	<div class="col-md-6">
 	 		@if (!(boolean)$campaign->is_reusable)
 	 		<table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('admin.coupon.code') }}</th>
                        <th>{{ trans('admin.coupon.is_used') }}</th>
                        <th>{{ trans('admin.coupon.consumer_name') }}</th>
                        <th>{{ trans('admin.coupon.datetime') }}</th>
                        <th>{{ trans('admin.coupon.discount_amount') }}</th>
                        <th>{{ trans('admin.coupon.salon') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;?>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ trans('admin.coupon.campaign.'.$coupon->is_used) }}</td>
                        <td>{{ !empty($coupon->couponBooking->booking->id) ? $coupon->couponBooking->booking->consumer->name : '-'; }}</td>
                        <td>{{ !empty($coupon->couponBooking->booking->id) ? str_date($coupon->couponBooking->booking->created_at) : '-'; }}</td>
                        <td>
                            {{ $coupon->discountAmount }}&euro;
                        </td>
                        <td>{{ !empty($coupon->couponBooking->booking->id) ? $coupon->couponBooking->booking->user->business->name : '-'}}</td>
                    </tr>
                    <?php $count++;?>
                    @endforeach
                </tbody>
            </table>
 	 		@endif
 	 	</div>
 	 	<div class="col-md-6">
 	 		<div id="used-chart" style="height: 350px; padding-bottom: 50px"></div>

 	 		<div id="date-used-chart" style="height: 350px; padding-bottom: 50px"></div>
 	 	</div>
 	 </div>
@stop
