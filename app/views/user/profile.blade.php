@extends ('layouts.dashboard')

@section('title')
    @parent :: {{ trans('user.profile.index') }}
@stop

@section ('scripts')
    @parent
    <script>
$(function () {
    $('a.delete-image').on('click', function (e) {
        e.preventDefault();
        if (confirm('{{ trans('common.are_you_sure') }}')) {
            var $this = $(this);
            $.ajax({
                url: $this.attr('href'),
                type: 'GET'
            }).done(function () {
                $this.parent().fadeOut(1000, function() {
                    $(this).remove();
                });
            });
        }
    });
});
    </script>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li {{ $activeTab === 'general' ? 'class="active"' : '' }}><a href="#general" role="tab" data-toggle="tab">{{ trans('user.profile.general') }}</a></li>
            <li {{ $activeTab === 'images' ? 'class="active"' : '' }}><a href="#images" role="tab" data-toggle="tab">{{ trans('user.profile.images') }}</a></li>
            <li {{ $activeTab === 'password' ? 'class="active"' : '' }}><a href="#password" role="tab" data-toggle="tab">{{ trans('user.change_password') }}</a></li>
        </ul>
        <br>
        @include ('el.messages')

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="general">
            {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>

            @foreach (['business_name', 'address', 'city', 'postcode', 'country', 'phone'] as $field)
                <div class="form-group {{ Form::errorCSS($field, $errors) }}">
                    {{ Form::label($field, trans('user.'.$field).Form::required($field, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
                    <div class="col-sm-6">
                        {{ Form::text($field, Input::get($field, $user->$field), ['class' => 'form-control']) }}
                        {{ Form::errorText($field, $errors) }}
                    </div>
                </div>
            @endforeach

                <div class="form-group">
                    <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_categories.index') }}</label>
                    <div class="col-sm-6">
                        @include ('user.el.categories')
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.description') }}</label>
                    <div class="col-sm-6">
                        {{ Form::textarea('description', Input::get('description', $user->description), ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_size" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_size') }}</label>
                    <div class="col-sm-6">
                        {{ Form::select('business_size', trans('user.profile.business_size_values'), $user->business_size, ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 text-right">
                        <input type="hidden" name="tab" value="general">
                        <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
            </div> <!-- General information -->

            <div class="tab-pane" id="images">
                <h3 class="comfortaa orange">{{ trans('user.profile.images') }}</h3>
                @if ($images->isEmpty())
                <p class="text-muted"><em>{{ trans('user.profile.no_images') }}</em></p>
                @endif

                <ul class="varaa-thumbnails">
                @foreach ($images as $image)
                    <li><a href="{{ route('images.delete', ['id' => $image->id]) }}" class="delete-image"><div class="overlay"><i class="fa fa-trash-o fa-3x"></i></div> <img src="{{ $image->getPublicUrl() }}" alt="" class="img-rounded"></a></li>
                @endforeach
                </ul>
                <div class="clearfix"></div>

                <h3 class="comfortaa orange">{{ trans('user.profile.upload_images') }}</h3>
                @include ('el.uploader', [
                    'formData' => $formData
                ])
            </div> <!-- Images -->

            <div class="tab-pane {{ $activeTab === 'password' ? 'active' : '' }}" id="password">
            {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <h3 class="comfortaa orange">{{ trans('user.change_password') }}</h3>
                @foreach ($fields as $name => $field)
                    <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
                    <div class="form-group {{ Form::errorCSS($name, $errors) }}">
                        {{ Form::label($name, $field['label'].Form::required($name, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
                        <div class="col-sm-6">
                    @if ($type === 'password') {{ Form::$type($name, ['class' => 'form-control']) }}
                    @else {{ Form::$type($name, Input::get($name), ['class' => 'form-control']) }}
                    @endif
                        {{ Form::errorText($name, $errors) }}
                        </div>
                    </div>
                @endforeach

                <div class="form-group">
                    <div class="col-sm-9 text-right">
                        <input type="hidden" name="tab" value="password">
                        <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
            </div> <!-- Change password -->
        </div>
    </div>
</div>
@stop
