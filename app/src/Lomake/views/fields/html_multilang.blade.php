<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    @foreach ($fields as $input)
        <li role="presentation" @if ($locale === $input['lang']) class="active" @endif><a href="#tab-html-multilang-{{ $input['lang'] }}" role="tab" data-toggle="tab">{{ $input['title'] }}</a></li>
    @endforeach
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
    @foreach ($fields as $input)
        <div role="tabpanel" class="tab-pane @if ($locale === $input['lang']) active @endif" id="tab-html-multilang-{{ $input['lang'] }}">{{ $input['textarea'] }}</div>
    @endforeach
    </div>

</div>
