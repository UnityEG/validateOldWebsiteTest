@extends('layouts.main')

@section('main')
@if ($errors->any())
<ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
    <div align="right">{{ link_to_route($RouteName.'.index', 'List all Gift Voucher Log') }}</div>
    <h1>Validate Gift Voucher</h1>
    <h2>{{ g::formatVoucherCode($giftvoucher->qr_code) }}</h2>
    <h4>This will be use number {{ $giftvoucher->used_times + 1 }} of {{ $giftvoucher->parameter->NoOfUses }} time(s).</h4>
    <div class="form-group">
        {{ Form::label('voucher_value', 'Voucher Value:') }}
        {{ Form::text ('voucher_value', g::formatCurrency($giftvoucher->voucher_value), array('class'=>'form-control', 'disabled')) }}
    </div>
    <div class="form-group">
        {{ Form::label('voucher_balance', 'Voucher Balance:') }}
        {{ Form::text ('voucher_balance', g::formatCurrency($giftvoucher->voucher_balance), array('class'=>'form-control', 'disabled')) }}
    </div>
    {{ Form::open(array('route' => $RouteName.'.store', 'role' => 'form', 'onsubmit'=>'Validate.disabled = true; Validate.value = "Please wait..."; return true;')) }}
    {{ Form::hidden ('giftvoucher_id', $giftvoucher->id)}}
    <div class="form-group">
        {{ Form::label('value', 'Value:') }}
        {{ Form::text ('value', g::formatCurrency($giftvoucher->voucher_balance), array('class'=>'form-control', 'id'=>'value')) }}
    </div>

    {{ Form::submit('Validate', array('class' => 'btn btn-primary', 'name' => 'Validate')) }}

    {{ Form::close() }}
</div>
@stop