<div class="search-wrapper hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="text-center">
                <ul id="js-navbar" class="nav navbar-nav front-nav">
                    @foreach ($categories as $category)
                    <li class="dropdown">
                        <a href="{{ $category->url }}" title="{{{ $category->name }}}">{{{ $category->name }}}
                    @if ($category->treatments->isEmpty() === false)
                        <span class="caret"></span>
                    @endif
                        </a>

                    @if ($category->treatments->isEmpty() === false)
                        <ul class="dropdown-menu" @if(!empty($category->background_image)) style="background: #fff url({{ $category->background_image }}) center center no-repeat; background-size: cover;" @endif>
                        @foreach ($category->treatments as $treatment)
                            <li><a href="{{ $treatment->url }}" title="{{{ $treatment->name }}}">{{{ $treatment->name }}}</a></li>
                        @endforeach
                        </ul>
                    @endif
                    </li>
                    @endforeach
                </ul>
                @if (App::environment() === 'tobook' || App::environment() === 'stag')
                <ul class="nav navbar-nav front-nav">
                    <li class="tutorial-video-link"><a class="view-video" title="ToBook.lv - {{ trans('home.video_tutorial_text')}}?" href="{{ Config::get('varaa.tutorial_video') }}">{{ trans('home.video_tutorial_text')}}</a></li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
