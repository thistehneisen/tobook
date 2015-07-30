@extends ('modules.as.layout')

@section ('content')
<h3>{{ trans('as.options.'.$page.'.index') }}</h3>

<div class="alert alert-info">
    <p><strong>{{ trans('as.options.'.$page.'.heading') }}</strong></p>
    <p>{{ trans('as.options.'.$page.'.info') }}</p>
</div>

<ul class="nav nav-tabs" role="tablist">
    @foreach ($sections as $index => $section)
    <li class="{{ $index === 0 ? 'active' : '' }}"><a href="#section-{{ $section }}" role="tab" data-toggle="tab">{{ trans('as.options.'.$page.'.'.$section) }}</a></li>
    @endforeach
</ul>
<br>

@include ('el.messages')

{{ Form::open(['route' => ['as.options', $page], 'class' => 'form-horizontal']) }}
<div class="tab-content">
    <?php $index = 0 ?>
    @foreach ($fields as $section => $controls)
    <div class="tab-pane {{ $index === 0 ? 'active' : '' }}" id="section-{{ $section }}">
        @foreach ($controls as $field)
        <div class="form-group">
            <label class="control-label col-sm-3">{{ trans('as.options.'.$page.'.'.$field->name) }}</label>
            <div class="col-sm-6">
                {{ $field->render() }}
                @if ($field->name === 'confirm_tokens_client' || $field->name === 'confirm_tokens_employee')
                <br/>
                <div class="alert alert-info">
                    {Services}, {ServicesFull}, {Name}, {BookingID}, {Phone}, {Email}, {Notes}, {CancelURL}, {Address}, {Deposit}
                </div>
                @elseif ($field->name === 'confirm_consumer_sms_message' || $field->name === 'confirm_employee_sms_message')
                <br/>
                <div class="alert alert-info">
                    {Services}, {ServicesFull}, {CancelURL}, {Address}, {Deposit}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <?php $index++; ?>
    @endforeach
</div>

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
