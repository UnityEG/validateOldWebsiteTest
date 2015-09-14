@extends('layouts.main')
@section('main')

<h1>Cart</h1>
@if (!Cart::count())
The Cart is empty. You can start {{ link_to_route('GiftVouchersParameters.index', 'purchase') }} vouchers.
@else

{{ Form::open(array('method' => 'POST', 'route' => array('payment.post'))) }}
{{ Form::submit('Checkout', array('class' => 'btn btn-danger')) }}
{{ link_to_route('Cart.createGiftVoucherAndClearCart', 'Purchase (Skip Payment Process for Fast Testing)', null, array('class' => 'btn btn-info')) }}
{{ Form::close() }}


<table class="table table-hover list-table">
    <thead>
        <tr>
            <th>Voucher</th>
            <th>Qty</th>
            <th>Value</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach (Cart::content() as $row)
        <tr>
            <td>{{ $row->name }} {{ $row->options->size	}}</td>
            <td>{{ $row->qty }}</td>
            <td>{{ g::formatCurrency($row->price) }}</td>
            <td>{{ g::formatCurrency($row->subtotal) }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3"></th>
            <th>{{ g::formatCurrency(Cart::total()) }}</th>
        </tr>
    </tbody>
</table>

{{ Form::open(array('method' => 'DELETE', 'route' => array('Cart.destroy', 1))) }}
{{ Form::submit('Clear Cart', array('class' => 'btn btn-danger', 'onclick' => 'return ConfirmMsg("This will be permanently clear the cart and cannot be recovered. Are you sure?")')) }}
{{ Form::close() }}


<script>
    function ConfirmMsg(msg) {
        var r = confirm(msg);
        if (r == true) {
            //txt = "You pressed OK!";
        } else {
            //txt = "You pressed Cancel!";
            return false;
        }
    }
</script>
@endif
@stop