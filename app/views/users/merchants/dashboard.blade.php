<?php extract( $data ); ?>
@extends('layouts.main')

@section('main')

<div class="col-md-6">
<div id="chartContainer" style="height: 300px; width: 100%;"></div>
</div>


<script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>

<script>
jQuery('document').ready(function ($) {
    //        chart drawing
    var loadingImage = "{{asset('css/loading.gif')}}";
    $("#chartContainer").html('<img src="' + loadingImage + '">');
    var ajaxUrl = "{{route('reports.merchant.active_vouchers_json', array($merchant->id))}}";
    $.ajax({
        url: ajaxUrl,
        success: function (data, textStatus, jqXHR) {
            drawChart(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown);
        }
    });

    function drawChart(rawData) {
        var preparedData = [];
        $.each(rawData, function (key, value) {
            title = value.title;
            price = value.value_validated;
            preparedData.push({label: title, y: price});
        });

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
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
            axisY: {
                prefix: "$"
            }
        });//var chart = new CanvasJS.Chart("chartContainer"

        chart.render();
    }//function drawChart(rawData)
});

</script>
@stop