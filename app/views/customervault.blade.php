@extends('layouts.main')
@section('main')
<style>
.clickable-row:hover{
cursor: pointer;
}
.clickable-row td{
padding-top:5px !important;
padding-bottom:5px !important;
}
</style>
<div class="forms-back">
<h3>My Vault</h3>
<a href="{{ URL::to('vaulthistory') }}"><span class="glyphicon glyphicon-briefcase"></span>History</a><br /><br />
	<table class="table table-hover">
    <thead>
    	<tr>
        	<th>Merchant</th>
	        <th>Voucher Type</th>
	        <th>Value</th>
	        <th>Expiry</th>
        </tr>
    </thead>
    <tbody>
        <tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 1</td>
          	<td>Voucher Type 1</td>
        	<td>Value 1</td>
        	<td>Expiry 1</td>
        </tr>
		<tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 2</td>
          	<td>Voucher Type 2</td>
        	<td>Value 2</td>
        	<td>Expiry 2</td>
        </tr>
		<tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 3</td>
          	<td>Voucher Type 3</td>
        	<td>Value 3</td>
        	<td>Expiry 3</td>
        </tr>
		<tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 4</td>
          	<td>Voucher Type 4</td>
        	<td>Value 4</td>
        	<td>Expiry 4</td>
        </tr>
		<tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 5</td>
          	<td>Voucher Type 5</td>
        	<td>Value 5</td>
        	<td>Expiry 5</td>
        </tr>
		<tr class='clickable-row' data-href='http://validate.co.nz/test/public/vaultdetails'>
        	<td>Merchant 6</td>
          	<td>Voucher Type 6</td>
        	<td>Value 6</td>
        	<td>Expiry 6</td>
        </tr>
    </tbody>
    </table>
</div>
<script>
$(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
});
</script>
@stop