@if ($layout)
    @extends ($layout)
@endif

@section ('content')
    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">General</a></li>
    @if ($item->is_business)
    <li role="presentation"><a href="#tab-business" role="tab" data-toggle="tab">Business Information</a></li>
    @endif
    <li role="presentation"><a href="#tab-services" role="tab" data-toggle="tab">Active Services</a></li>
</ul>

<br>
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tab-general">
        {{ $lomake->open() }}
        {{ $lomake->render() }}
        {{ $lomake->close() }}
    </div> <!-- tab general -->

    @if ($item->is_business)
    <div role="tabpanel" class="tab-pane" id="tab-business">
        @include ('user.el.business')
    </div> <!-- tab business -->
    @endif

    <div role="tabpanel" class="tab-pane" id="tab-services">
    </div> <!-- tab services -->
</div>

<div class="modal-form">
    @include ('el.messages')

</div>
@stop
