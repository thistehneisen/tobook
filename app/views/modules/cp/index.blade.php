@extends ('layouts.default')

@section ('scripts')
    @parent
@stop

@section('content')
{{ Form::open(['route' => 'payment.process']) }}
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
        <button type="submit" class="btn btn-success">Place order</button>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4">
    </div>
</div>
{{ Form::close() }}
@stop
