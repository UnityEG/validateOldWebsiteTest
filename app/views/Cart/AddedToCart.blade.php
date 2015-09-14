@extends('layouts.main')
@section('main')
<h1>Cart</h1>

<strong>Voucher added to Cart.</strong> 
You can view your {{ link_to_route('Cart.index', 'cart') }}, or 
continue {{ link_to_route('GiftVouchersParameters.index', 'purchase') }} another voucher.;

@stop