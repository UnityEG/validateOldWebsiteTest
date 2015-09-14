<?php extract( $data ); ?>
@extends('layouts.main')

@section('main')
<style>
    .canvasjs-chart-credit{
        display: none;
    }
</style>
<h1>Active Vouchers</h1>

<div class="form-group">
    {{Form::label('time_filter', 'Choose Time:')}}
    {{Form::select('time_filter', $time_filters, array($time_filter_applied), array('class'=>'form-control'))}}
    <div id="specific_period" style="display:none;">
        {{Form::label('date_from', 'From:')}}
        {{Form::text('date_from', null, array('id'=>'date_from', 'placeholder'=>'dd/mm/yyyy'))}}
        {{Form::label('date_to', 'To:')}}
        {{Form::text('date_to', null, array('id'=>'date_to', 'placeholder'=>'dd/mm/yyyy'))}}
    </div>
</div>
@if(isset($time_filter_applied) && !empty($time_filter_applied))
<div class="alert alert-danger">
    Filter: {{{$custom_time_filter_applied.ucfirst(str_replace('_', ' ', $time_filter_applied))}}}
    <a href="{{route('reports.merchant.active_vouchers', array($merchant_id))}}"><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</div>

@endif
<table class="table table-bordered table-hover table-striped">
    <tbody>
        <tr>
            <th>Active Vouchers</th>
            <th>Qty Sold</th>
            <th>Validations</th>
            <th>Valid Outstanding</th>
            <th>Value Validated</th>
        </tr>
        <?php
        $total_qty_sold                   = 0;
        $total_validated                  = 0;
        $total_valid_vouchers_outstanding = 0;
        $total_value_validated            = 0;
        ?>
    
        @foreach($active_gift_vouchers as $voucher)
        <tr>
            <td>
                <span class="chartTitle">{{{$voucher->Title}}}</span>
            </td>

            <td>
                <?php
                $qty_sold                         = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->count();
                $total_qty_sold += $qty_sold;
                ?>
                {{{$qty_sold}}}

            </td>

            <td>
                <?php
                $vouchers_sold                    = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->get();
                $validated                        = 0;
                foreach ( $vouchers_sold as $voucher_sold ) {
                    $validated += GiftVoucherValidation::where( 'giftvoucher_id', $voucher_sold->id )->count();
                }

                $total_validated += $validated;
                ?>
                {{{$validated}}}
            </td>

            <td>

                <?php
                $valid_vouchers_outstanding = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->where( 'status', 1 )->count();
                $total_valid_vouchers_outstanding += $valid_vouchers_outstanding;
                ?>

                {{{$valid_vouchers_outstanding}}}
            </td>

            <td>
                <?php
                if ( $voucher->SingleUse == 1 ) {
//                        for single use voucer we get the the voucher value directly without calculating the value each time the voucher validated.
                    $vouchers_sold   = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->get();
                    $value_validated = 0;
                    foreach ( $vouchers_sold as $voucher_sold ) {
                        $validation_for_single_voucher = GiftVoucherValidation::where( 'giftvoucher_id', $voucher_sold->id )->exists();
                        $value_validated += ($validation_for_single_voucher) ? $voucher_sold->voucher_value : 0;
                    }//foreach ( $vouchers_sold as $voucher_sold)
                    $total_value_validated += $value_validated;
                }//if ($voucher->SingleUse == 0 )
                else {
//                        for multiuse voucher we have to calculate the value spent each time the voucher validated.
                    $vouchers_sold   = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->get();
                    $value_validated = 0;
                    foreach ( $vouchers_sold as $voucher_sold ) {
                        $validations_for_each_voucher_sold = GiftVoucherValidation::where( 'giftvoucher_id', $voucher_sold->id )->get();
                        foreach ( $validations_for_each_voucher_sold as $validation ) {
                            $value_validated += $validation->value;
                        }//foreach ( $validations_for_each_voucher_sold as $validation )
                    }//foreach ( $vouchers_sold as $voucher_sold)
                    $total_value_validated += $value_validated;
                }
                ?>
                ${{$value_validated}}
            </td>
        </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <td>{{{$total_qty_sold}}}</td>
            <td>{{{$total_validated}}}</td>
            <td>{{{$total_valid_vouchers_outstanding}}}</td>
            <td>${{{$total_value_validated}}}</td>
        </tr>
    </tbody>
</table>

<div id="chartContainer" style="height: 300px; width: 100%;"></div>

<script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>

<script>
    jQuery('document').ready(function ($) {
        
        $("#date_from").datepicker({
            dateFormat: "dd/mm/yy"
        });
        
        $("#date_to").datepicker({
            dateFormat: "dd/mm/yy"
        });
        
        var url = "{{route('reports.merchant.active_vouchers', array($merchant_id))}}";
        
        
        $("#time_filter").change(function () {
            var filter = $(this).val();
            if ( 'custom_period' === filter ) {
                var date_from = '';
                var date_to = '';

                $("#specific_period").show();

                $("#date_from").change(function () {
                    date_from = $(this).val();
                    
                    if ( '' !== date_from && '' !== date_to ) {
                        url += '?time_filter=' + filter + '&date_from=' + date_from + '&date_to=' + date_to;
                        window.location.replace(url);
                    }//if ('' !== date_from && '' !== date_to)
                });//$("date_from").change(function()

                $("#date_to").change(function () {
                    date_to = $(this).val();

                    if ( '' !== date_from && '' !== date_to ) {
                        url += '?time_filter=' + filter + '&date_from=' + date_from + '&date_to=' + date_to;
                        window.location.replace(url);
                    }//if ('' !== date_from && '' !== date_to)
                });//$("#date_to").change(function()


            }//if ('custom_period' === filter)
            else {
                $("#specific_period").hide();
                url += '?time_filter=' + filter;
                window.location.replace(url);
            }


        });//$("#time_filter").change(function ()
        
        
//        chart drawing
        var loadingImage = "{{asset('css/loading.gif')}}";
        $("#chartContainer").html('<img src="' + loadingImage + '">');
        var ajaxUrl = "{{route('reports.merchant.active_vouchers_json', array($merchant_id))}}";
        $.ajax({
            url: ajaxUrl,
            success: function (data, textStatus, jqXHR) {
                        console.log(textStatus);
                        drawChart(data);
                    },
            error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
                    }
        });
        
        function drawChart(rawData){
            preparedData = [];
            $.each(rawData, function(key, value){
                title = value.title;
                price = value.value_validated;
                preparedData.push({label:title, y:price});
            });
            
            var chart = new CanvasJS.Chart("chartContainer", {

                title:{
                  text: "Vouchers sold"              
                },
                data: [//array of dataSeries              
                  { //dataSeries object

                   /*** Change type "column" to "bar", "area", "line" or "pie"***/
                   type: "column",
                   dataPoints: preparedData
                 }
                 ],
                 /** Set axisY properties here*/
              axisY:{
                prefix: "$"
              }    
           });//var chart = new CanvasJS.Chart("chartContainer"

          chart.render();
        }
        
        
    });//jQuery('document').ready(function($)
</script>


@stop
