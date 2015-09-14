<?php extract($data); ?>

@extends('layouts.main')

@section('main')

<h1>Validations Report</h1>

<table class="table table-bordered table-hover table-striped">
    <thead>
        <th>Voucher Number</th>
        <th>Purchase Date</th>
        <th>Expiry Date</th>
        <th>Value</th>
        <th>Validation Date</th>
    </thead>
    
    <tbody>
        @foreach($data as $row_data)
        <tr>
            <td><a href="{{route('GiftVoucher.show', array($row_data['sold_voucher_id']))}}">{{{$row_data['voucher_number']}}}</a></td>
            <td>{{{$row_data['pruchase_date']}}}</td>
            <td>{{{$row_data['expiry_date']}}}</td>
            <td>{{{$row_data['value']}}}</td>
            <td>{{{$row_data['validation_date']}}}</td>
        </tr>
        @endforeach
    </tbody>
    
</table>

@stop
