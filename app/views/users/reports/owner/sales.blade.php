<?php 
$data = (new ReportController())->ownerReportSales();
extract($data);
?>
<style>
    .row{
        width: 100%;
        margin-left:0;
    }
    .chartHeader .right p{
        float: right;
        font-weight: bold;
        color: black;
        margin-bottom: 0.5em;
    }
    
    .chartHeaderLinks{
        clear: both;
    }
    
    .chartHeaderLinks a{
        margin: 0 0.5em;
    }
    
    .chartPeriodButtons{
        margin-top: 0.5em;
        float: right;
    }
    
    .chartPeriodButtons span{
        margin-right: 0.5em;
    }
    
    .chartPeriodButtons button{
        margin: 0 -2px;
        font-weight: bold;
        border: 1px solid rgb(218, 218, 218);
        background-color: rgba(234, 234, 234, 0.52);
    }
    
    .chartPeriodButtons button:focus{
        background: gray;
        background: -webkit-linear-gradient(black, gray); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(black, gray); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(black, gray); /* For Firefox 3.6 to 15 */
        background: linear-gradient(black, gray); /* Standard syntax */
        color: white;
        border: 1px solid rgb(218, 218, 218);
        outline: 1px solid rgb(218, 218, 218);
    }
    
    .btn-active{
        background: gray;
        background: -webkit-linear-gradient(black, gray); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(black, gray); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(black, gray); /* For Firefox 3.6 to 15 */
        background: linear-gradient(black, gray); /* Standard syntax */
        color: white;
        outline: 1px solid rgb(218, 218, 218);
        outline-offset: -2px;
    }
    
    .tableHeader{
        font-weight: bold;
        margin: 0.5em 0;
    }
    
    @media (min-width: 1000px){
        .canvasjs-chart-canvas {
            width: 100%;
            margin-left: 0;
        }
    }
    
    .canvasjs-chart-credit{
        display: none;
    }
</style>

<div class="row">
    <div class="row chartHeader">
        <div class="right">
            <p>{{Carbon::now('Pacific/Auckland')->formatLocalized('%A, %d %B %Y');}}</p>
            <section class="chartHeaderLinks">
                <a href="#">&Lt;Previous week</a>
                <a href="#">&lt;Previous Day</a>
                <a href="#">Select</a>
                <a href="#">Next Day&gt;</a>
                <a href="#">Next Week&Gt;</a>
            </section>
            <section class="chartPeriodButtons">
                <span>Select Period:</span>
                <button class="btn btn-toolbar btn-sm">Day</button>
                <button class="btn btn-toolbar btn-sm">Week</button>
                <button class="btn btn-toolbar btn-sm">Month</button>
            </section>
        </div>
    </div>
</div>
<!--<div class="form-group">
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
    <a href="{{route('reports.owner.sales')}}"><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</div>
@endif-->
<div class="row">
<table class="table table-bordered table-hover table-striped table-responsive">
    <thead>
    <h5 class="tableHeader">Sales Report</h5>
        <th>Voucher Type</th>
        <th></th>
        <th>Quantity</th>
        <th>Value</th>
        <th>Commission</th>
    </thead>
<tbody>
    @foreach($data['table_content'] as $row_data)
    @if('Gift Vouchers' == $row_data['voucher_type'])
    <tr>
        <td rowspan="2">{{$row_data['voucher_type']}}</td>
        <td>{{$row_data['online']['voucher_sub_type']}}</td>
        <td>{{$row_data['online']['quantity']}}</td>
        <td>{{g::formatCurrency($row_data['online']['value'])}}</td>
        <td>{{$row_data['online']['commission']}}</td>
    </tr>
    <tr>
        <td>{{$row_data['instore']['voucher_sub_type']}}</td>
        <td>{{$row_data['instore']['quantity']}}</td>
        <td>{{$row_data['instore']['value']}}</td>
        <td>{{$row_data['instore']['commission']}}</td>
    </tr>
    @else
    <tr>
        <td colspan="2">
            {{$row_data['voucher_type']}}
        <td>
            {{$row_data['quantity']}}
        </td>
        <td>
            {{$row_data['value']}}
        </td>
        <td>
            {{$row_data['commission']}}
        </td>
    </tr>
    @endif
    @endforeach
    <tr>
        <td colspan="2">Total</td>
        <td>{{$data['total_row']['total_quantity']}}</td>
        <td>{{g::formatCurrency($data['total_row']['total_value'])}}</td>
        <td>{{g::formatCurrency($data['total_row']['total_commission'])}}</td>
    </tr>
</tbody>
</table>
</div>
    
<div id="chartCanvas" class="row">
    <div class="row">
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    </div>
</div>
<script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>

<script>
jQuery('document').ready(function ($) {

    $("#date_from").datepicker({
        dateFormat: "dd/mm/yy"
    });

    $("#date_to").datepicker({
        dateFormat: "dd/mm/yy"
    });

    var url = "{{route('reports.owner.sales')}}";


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

    
});//jQuery('document').ready(function($)
</script>

<script>
    "use strict";
    (function(){
        jQuery('document').ready(function($){
            //        chart drawing
            var loadingImage = "{{asset('css/loading.gif')}}";
            var ajaxUrl = "{{route('reports.owner.sales.hourly')}}";
            $("#chartContainer").html('<img src="' + loadingImage + '">');
            $.ajax({
                url: ajaxUrl,
                success: function (data, textStatus, jqXHR) {
                    console.log(textStatus);
                    drawChart(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
                }
            });//$.ajax({
            $("#reportHeader button").click(function(e){
                e.preventDefault();
                $("#reportHeader button").removeClass("btn-active disabled");
                $(this).addClass("btn-active disabled");
                switch ($(this).text()){
                    case "Day":
                        var ajaxUrlModified = ajaxUrl + "?time_filter=today";
                        break;
                    case "Week":
                        var ajaxUrlModified = ajaxUrl + "?time_filter=this_week";
                        break;
                    case "Month":
                        var ajaxUrlModified = ajaxUrl + "?time_filter=this_month";
                        break;
                    default :
                        var ajaxUrlModified = ajaxUrl;
                }//switch ($(this).text())
                $.ajax({
                    url: ajaxUrlModified,
                    success: function (data, textStatus, jqXHR) {
                        console.log(textStatus);
                        drawChart(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
                    }
                });//$.ajax({
            });//$("#chartCanvas button").click(function(e)
            
            
            function drawChart(rawData) {
                var preparedData = [];
                $.each(rawData, function (key, value) {
//                    if ( 'Gift Vouchers' === value.voucher_type ) {
//                       var title = value.voucher_type;
//                       var economic_value = value.online.value;
//                    }//if ('Gift Vouchers' === value.voucher_type)
//                    else {
//                        var title = value.voucher_type;
//                        var economic_value = value.value;
//                    }
                    var title = value.time_auckland;
                    var economic_value = value.value;
                    preparedData.push({label: title, y: economic_value});
                });//$.each(rawData.table_content, function(key, value)
                
                var chart = new CanvasJS.Chart("chartContainer", {
                    title: {
                        text: "Sales Report"
                    },
                    data: [//array of dataSeries              
                        { //dataSeries object

                            /*** Change type "column" to "bar", "area", "line" or "pie"***/
                            type: "column",
                            dataPoints: preparedData
                        }
                    ],
                    /** Set axisY properties here*/
                    axisY: {
                        prefix: "$"
                    }
                });//var chart = new CanvasJS.Chart("chartContainer"

                chart.render();
            }//function drawChart(rawData)
        });//jQuery('document').ready(function($)
    })();
    
    
</script>



