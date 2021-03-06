@extends('layouts.main')
@section('main')
<div class="forms-back">
<div class="col-md-6">
<table class="table table-bordered">
    	<tr>
        	<th>Voucher Code</th>
        	<th>396 - 930 - 411</th>
        </tr>
    	<tr>
	        <th>Merchant</th>
        	<td>Breakers Kapiti</td>
        </tr>
    	<tr>
	        <th>Customer</th>
        	<td>Customer 4 name</td>
        </tr>
    	<tr>
	        <th>Delivery Date</th>
          	<td>24/07/2015 00:00:00</td>
        </tr>
    	<tr>
	        <th>Voucher Value</th>
          	<td>$100.00</td>
        </tr>
    	<tr>
	        <th>Voucher Balance</th>
          	<td>$50.00</td>
        </tr>
    	<tr>
	        <th>Last Validation Date</th>
          	<td>2015-07-24 02:31:43</td>
        </tr>
    </table>
</div>
<div class="col-md-6">
<table class="table table-bordered">
    	<tr>
        	<th>Status</th>
        	<th>Valid</th>
        </tr>
    	<tr>
	        <th>Gift Voucher Title</th>
        	<td>Gift Voucher</td>
        </tr>
    	<tr>
	        <th>Recipient Email</th>
          	<td>brent@posybl.co.nz</td>
        </tr>
    	<tr>
	        <th>Expiry Date</th>
          	<td>24/07/2016 00:00:00</td>
        </tr>
    	<tr>
	        <th>Numberc of Uses</th>
          	<td>Unlimited</td>
        </tr>
    	<tr>
	        <th>Number of Used Times</th>
          	<td>1</td>
        </tr>
    	<tr>
	        <th>Number of Uses Left</th>
          	<td>Unlimited</td>
        </tr>
    </table>
</div>
<a href="{{ URL::to('myvault') }}"><span class="glyphicon glyphicon-arrow-left"></span>Back To Vault</a>
</div>

@stop