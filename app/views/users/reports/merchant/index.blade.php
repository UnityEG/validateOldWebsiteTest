<?php extract($data); ?>
@extends('layouts.main')

@section('main')

<h1>Welcome to the Reports page</h1>
<p>{{link_to_route('reports.merchant.active_vouchers', 'Active Vouchers Report', array($merchant->id))}}</p>

<p>{{link_to_route('reports.merchant.validations', 'Validations Report', array($merchant->id))}}</p>
@stop