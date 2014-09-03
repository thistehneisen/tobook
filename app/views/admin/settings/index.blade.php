@extends ('layouts.admin')

@section('content')
    {{ Form::open(['route' => ['admin.settings.index'], 'class' => 'form-horizontal']) }}
<div class="form-group">
    <label class="col-sm-2 control-label" for="location">Location</label>
    <div class="col-sm-10">
    {{ Form::text('location', Input::get('location', $location), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="location">Freephone</label>
    <div class="col-sm-10">
    {{ Form::text('free-phone', Input::get('free-phone', $freephone), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="location">Telephone</label>
    <div class="col-sm-10">
    {{ Form::text('telephone', Input::get('telephone', $telephone), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="location">Fax</label>
    <div class="col-sm-10">
    {{ Form::text('fax', Input::get('fax', $fax), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="facebook-page">Facebook page</label>
    <div class="col-sm-10">
    {{ Form::text('facebook-page', Input::get('facebook-page', $facebook_page), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="google-page">Google page</label>
    <div class="col-sm-10">
    {{ Form::text('google-page', Input::get('google-page', $google_page), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="google-page">Pinterest page</label>
    <div class="col-sm-10">
    {{ Form::text('pinterest-page', Input::get('pinterest-page', $pinterest_page), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="google-page">Linkedin page</label>
    <div class="col-sm-10">
    {{ Form::text('linkedin-page', Input::get('linkedin-page', $linkedin_page), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="google-page">RSS page</label>
    <div class="col-sm-10">
    {{ Form::text('rss-page', Input::get('rss-page', $rss_page), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</div>
    {{ Form::close() }}
@stop
