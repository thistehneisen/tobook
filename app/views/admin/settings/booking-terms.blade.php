@extends ('layouts.admin')
@section ('scripts')
{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
@stop

@section('content')

<h3>{{ trans('admin.nav.booking_terms') }}</h3>

{{ Form::open(['route' => 'admin.booking.terms', 'class' => 'form-horizontal well']) }}
    <div class="form-group">
        <label class="control-label col-sm-2">@lang('admin.settings.booking_terms')</label>
        <div class="col-sm-10">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                @foreach ($terms as $lang => $term)
                    <li role="presentation" class="{{ $term->active }}"><a href="#tab-html-multilang-{{ $lang }}" role="tab" data-toggle="tab">{{ $term->title }}</a></li>
                @endforeach
                </ul>

                <div class="tab-content">
                @foreach ($terms as $lang => $term)
                    <div role="tabpanel" class="tab-pane {{ $term->active }}" id="tab-html-multilang-{{ $lang }}">
                        <textarea name="content[{{ $lang }}]" rows="10" class="form-control">{{ $term->content }}</textarea>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-primary" type="submit">@lang('common.save')</button>
        </div>
    </div>
{{ Form::close() }}
@stop
