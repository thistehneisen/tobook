<h3 class="comfortaa orange">{{ trans('user.profile.upload_images') }}</h3>
@include ('el.uploader', ['formData' => $formData])

<div class="clearfix"></div>
<h3 class="comfortaa orange">{{ trans('user.profile.images') }}</h3>
@if ($images->isEmpty())
<p class="text-muted"><em>{{ trans('user.profile.no_images') }}</em></p>
@endif

<ul class="varaa-thumbnails">
@foreach ($images as $image)
    <li><a href="{{ route('images.delete', ['id' => $image->id]) }}" class="delete-image"><div class="overlay"><i class="fa fa-trash-o fa-3x"></i></div> <img src="{{ $image->getPublicUrl() }}" alt="" class="img-rounded"></a></li>
@endforeach
</ul>
