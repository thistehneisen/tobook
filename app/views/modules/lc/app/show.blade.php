<h3 id="consumerName">{{ $consumer->consumer->getNameAttribute() }}</h3>
<span id="consumerEmail">{{ $consumer->consumer->email }}</span> /
<span id="consumerPhone">{{ $consumer->consumer->phone }}</span>
<p id="consumerPoints">{{{ trans('Points') }}}: {{ $consumer->total_points }}</p>
<hr />
<button class="btn btn-default btn-success" id="givePoints">{{ trans('Give Points') }}</button>
<h3>{{ trans('Awards') }}</h3>
<div id="pointList">
    @foreach ($vouchers as $key => $value)
    <div id="usePoint">
        <div class="col-md-7">
            <div id="pointName">{{ $value->name }}</div>
            <div id="scoreRequired">{{ $value->required }}</div>
        </div>
        <div class="col-md-5"><button class="btn btn-default btn-info" id="use">{{ trans('Use point') }}</button></div>
    </div>
    @endforeach
</div>
<h3>{{ trans('Stamps') }}</h3>
<div id="stampList">
    <div id="useStamp">
        @foreach ($offers as $key => $value)
        <div class="col-md-4">
            <button class="btn btn-default btn-info">{{ trans('Add') }}</button>
            <button class="btn btn-default btn-info">{{ trans('Use') }}</button>
        </div>
        <div class="col-md-8">
            <span id="currentStamp"></span> /
            <span id="stampRequired"></span>{{{ $value->required }}}&nbsp;
            <span id="stampName">{{{ $value->name }}}</span>
        </div>
        @endforeach
    </div>
</div>
<button class="btn btn-default btn-success" id="createCard">{{ trans('Create new loyalty card') }}</button>
<button class="btn btn-default btn-success">{{ trans('Back') }}</button>
