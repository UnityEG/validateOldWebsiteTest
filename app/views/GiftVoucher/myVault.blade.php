<?php $route = 'GiftVoucher'; ?>
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
    @if (!$group->count())
    There are no gift voucher in the vault.
    @else
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
            @foreach ($group as $item)
            <?php include(app_path() . '/views/GiftVoucher/_inc_item.php'); ?>
            <tr class='clickable-row' data-href='{{route($route . '.show', array($item->id))}}'>
                <td>{{ $item->MerchantBusinessName              }}</td>
                <td>{{ $item->gift_vouchers_parameters_Title    }}</td>
                <td>{{ g::formatCurrency($item->voucher_value)  }}</td>
                <td>{{ g::formatDateTime($item->expiry_date)	}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $group->links() }}
    @endif
</div>
<script>
    $(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.document.location = $(this).data("href");
        });
    });
</script>
@stop