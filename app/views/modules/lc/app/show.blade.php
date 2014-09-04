<h3>{{ $consumer->consumer->getNameAttribute() }}</h3>
<span>{{ $consumer->consumer->email }}</span> /
<span>{{ $consumer->consumer->phone }}</span>
<p>{{{ trans('Points') }}}: {{ $consumer->total_points }}</p>
<hr />
<span data-consumerid='{{{ $consumer->consumer->id }}}'>{{{ $consumer->consumer->id }}}</span>
<button class="btn btn-default btn-success full" data-toggle="modal" data-target="#js-giveScoreModal">{{ trans('Give Points') }}</button>

<div class="full"><h3>{{ trans('Awards') }}</h3>
    @foreach ($vouchers as $key => $value)
    <div class="data-row">
        <div class="col-md-7">
            <div>{{ $value->name }}</div>
            <div class="required">{{ trans('Point required: ') }}{{ $value->required }}</div>
        </div>
        <div class="col-md-5"><button class="btn btn-default btn-info" id="js-useVoucher" data-consumerid='{{{ $consumer->consumer->id }}}'>{{ trans('Use voucher') }}</button></div>
    </div>
    @endforeach
</div>
<div class="full"><h3>{{ trans('Stamps') }}</h3>
    @foreach ($offers as $key => $value)
    <div class="data-row">
        <div class="col-md-5">
            <button class="btn btn-default btn-info">{{ trans('Add') }}</button>
            <button class="btn btn-default btn-info">{{ trans('Use') }}</button>
        </div>
        <div class="col-md-7">
            @if ($stampInfo === null)
            <span class="col-md-4 required">0/{{{ $value->required }}}</span>
            @else
            <span class="col-md-4 required">{{ $stampInfo[$value->id][0] }}/{{{ $value->required }}}</span>
            @endif
            <span class="col-md-8">{{{ $value->name }}}</span>
        </div>
    </div>
    @endforeach
</div>
<button class="btn btn-default btn-success full">{{ trans('Create new loyalty card') }}</button>
<button class="btn btn-default btn-success">{{ trans('Back') }}</button>
