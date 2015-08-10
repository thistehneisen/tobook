@extends ('layouts.default')

@section('scripts')
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section('content')
<div class="container">
    <h1 class="comfortaa orange text-center">{{ trans('cp.success') }}</h1>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            @if (App::environment() !== 'stag')
            <div class="alert alert-success">
                <p>{{ trans('cp.success_notice') }}</p>
            </div>
            @else
            @include ('front.cart.el.show-on-thankyou')
            @if(!empty($cart))
            <table class="table table-striped">
                <tbody>
                    @foreach ($cart->details as $detail)
                        @if ($detail->model !== null)
                        <tr class="cart-detail" id="cart-detail-{{ $detail->id }}">
                            <td>{{{ $detail->name }}}</td>
                            <td>{{{ $detail->deposit }}}{{ Settings::get('currency') }}</td>
                            <td></td>
                            <td>{{{ $detail->price }}}{{ Settings::get('currency') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @endif
            @endif
        </div>
    </div>
</div>
@stop
