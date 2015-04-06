<div class="search-wrapper">
    <div class="container">
        <div class="row">
            <div class="text-center">
                <ul class="nav navbar-nav front-nav">
                    @foreach ($categories as $category)
                    <li class="dropdown">
                        <a href="{{ $category->url }}" title="{{ $category->name }}">{{ $category->name }}
                    @if ($category->treatments->isEmpty() === false)
                        <span class="caret"></span>
                    @endif
                        </a>

                    @if ($category->treatments->isEmpty() === false)
                        <ul class="dropdown-menu" @if(!empty($category->background_image)) style="background: #fff url({{ $category->background_image }}) center center no-repeat; background-size: cover;" @endif>
                        @foreach ($category->treatments as $treatment)
                            <li><a href="#">{{ $treatment->name }}</a></li>
                        @endforeach
                        </ul>
                    @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
