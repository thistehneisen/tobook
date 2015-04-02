<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    @foreach ($fields as $index => $input)
        <li role="presentation" @if ($index === 0) class="active" @endif><a href="#tab-html-multilang-{{ $input['lang'] }}" role="tab" data-toggle="tab">{{ $input['title'] }}</a></li>
    @endforeach
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
    @foreach ($fields as $index => $input)
        <div role="tabpanel" class="tab-pane @if ($index === 0) active @endif" id="tab-html-multilang-{{ $input['lang'] }}">{{ $input['textarea'] }}</div>
    @endforeach
    </div>

</div>
