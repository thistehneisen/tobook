<footer class="container-fluid footer hidden-print">
    <div class="container text-center">
        <p>&copy; {{ date('Y') }}
<a href="{{{ Settings::get('copyright_url') }}}" target="_blank">{{{ Settings::get('copyright_name') }}}</a>
| <a href="/about" target="_blank">{{ trans('common.about') }}</a>
| <a href="/business" target="_blank">{{ trans('common.business') }}</a>
| <a href="/intro" target="_blank">{{ trans('common.intro') }}</a>
        </p>
        <ul class="list-unstyled list-inline list-social-networks">
            @foreach (Settings::group('social') as $name => $url)
                @if ($url)
                <li><a href="{{{ $url }}}" target="_blank"><i class="fa fa-{{{ $name }}}"></i></a></li>
                @endif
            @endforeach
        </ul>
    </div>
</footer>
