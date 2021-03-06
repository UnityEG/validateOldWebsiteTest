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
<h3>My History</h3>
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
        <tr class='clickable-row' >
        	<td>Merchant 1</td>
          	<td>Voucher Type 1</td>
        	<td>Value 1</td>
        	<td>Expiry 1</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 2</td>
          	<td>Voucher Type 2</td>
        	<td>Value 2</td>
        	<td>Expiry 2</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 3</td>
          	<td>Voucher Type 3</td>
        	<td>Value 3</td>
        	<td>Expiry 3</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 4</td>
          	<td>Voucher Type 4</td>
        	<td>Value 4</td>
        	<td>Expiry 4</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 5</td>
          	<td>Voucher Type 5</td>
        	<td>Value 5</td>
        	<td>Expiry 5</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 6</td>
          	<td>Voucher Type 6</td>
        	<td>Value 6</td>
        	<td>Expiry 6</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 7</td>
          	<td>Voucher Type 7</td>
        	<td>Value 7</td>
        	<td>Expiry 7</td>
        </tr>
		<tr class='clickable-row' >
        	<td>Merchant 7</td>
          	<td>Voucher Type 7</td>
        	<td>Value 7</td>
        	<td>Expiry 7</td>
        </tr>
    </tbody>
    </table>
<a href="{{ URL::to('myvault') }}"><span class="glyphicon glyphicon-arrow-left"></span>Back To Vault</a>
</div>

@stop