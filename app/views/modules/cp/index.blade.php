@extends ('layouts.default')

@section ('scripts')
    @parent
@stop

@section('content')
{{ Form::open(['route' => 'payment.purchase']) }}
<div class="container">
    <div class="col-sm-8 col-md-8 col-lg-8">
        <h4>Your order</h4>
        <table class="table">
            <tfoot>
                <tr>
                    <td>Subtotal</td>
                    <td>{{ $transaction->formatted_amount }}</td>
                </tr>
            </tfoot>
        </table>
{{--
        <h4>Payment information</h4>

        <div class="form-group row">
            <div class="col-sm-12 {{ Form::errorCSS('number', $errors) }}">
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                    <input class="form-control" name="number" type="text" placeholder="Card number">
                </div>
                {{ Form::errorText('number', $errors) }}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6 {{ Form::errorCSS('exp', $errors) }}">
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-calendar-o"></i></div>
                    <input class="form-control" name="exp" type="text" placeholder="MM/YYYY">
                </div>
                {{ Form::errorText('exp', $errors) }}
            </div>
            <div class="col-sm-6 {{ Form::errorCSS('cvv', $errors) }}">
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                    <input class="form-control" name="cvv" type="text" placeholder="CVV">
                </div>
                {{ Form::errorText('cvv', $errors) }}
            </div>
        </div>
--}}
        <button type="submit" class="btn btn-success">Place order</button>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4">
    </div>
</div>
{{ Form::close() }}
@stop
