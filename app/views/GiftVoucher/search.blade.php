<?php $route = 'GiftVoucher'; ?>
@extends('layouts.main')

@section('main')
@if ($errors->any())
<ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif

    <!--
    
    {{ Form::open(array('route' => $route.'.result', 'role' => 'form')) }}
        <div class="form-group">
            {{ Form::label('qr_code', 'Voucher Code:') }}
            {{ Form::text ('qr_code', null, array('class'=>'form-control', 'id'=>'qr_code')) }}
        </div>
    
        {{ Form::submit('Check', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
    -->
  
    <!-- <link href="bootstrap.css" rel="stylesheet"/> -->

    <style>
		.nums{margin-right:2px; min-width:528px !important;}
        .numbut{padding:28px 45px !important; background-color: #000 !important; width:30px; height:25px}
        .num{max-width:172px !important; height:80px !important; font-size:30px; min-width:172px !important; font-size:30px !important; text-align:center;}
        .checkbut{padding:28px 40px !important; background-color: #000 !important; width:437px !important; height:30px; margin: 10px 2px 5px 0px; }
        .zbut{width:160px !important;}
        .dotbut{padding:28px 47px !important; background-color: #000 !important; width:29px; height:25px}
        .delbut,.clrbut{height:158px !important; width:105px !important; background-color:#F0F0F0 !important; margin:5px;}
		table tr td{border:0px; text-align:center; padding:0px !important;}
    </style>

    <div align="center" class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="nums">
    {{ Form::open(array('route' => $route.'.result', 'role' => 'form', 'onkeypress'=>'updateQRcode()', 'style'=>'padding:0; margin:0;', 'class'=>'form-inline')) }}
            <h2> Enter Voucher Code </h2> <br />
            <div class="input-group" >
                <input maxlength="3" onkeyup="moveCursor(event)"   id="input1" type="text" class="form-control num" placeholder="">
                <input maxlength="3" onkeyup="moveCursor(event)"   id="input2" type="text" class="form-control num" placeholder="">
                <input maxlength="3" onkeyup="moveCursor(event)"   id="input3" type="text" class="form-control num" placeholder="">
            </div> 
                    <div class="form-group" style="display: none;">
                    <!--<div class="form-group">-->
                        {{ Form::label('qr_code', 'Voucher Code:') }}
                        {{ Form::text ('qr_code', null, array('class'=>'form-control', 'id'=>'qr_code')) }}
                    </div>
                    <button type="submit" id="check" value="check" class="btn btn-primary checkbut">CHECK</button>
    {{ Form::close() }}
        </div>
        <table >
            <tr> <td> <button onClick="addNum(this);" id="7" value="7"  class="btn btn-primary numbut">7</button></td>
                <td><button onClick="addNum(this);" id="8" value="8" class="btn btn-primary numbut">8</button></td>
                <td><button onClick="addNum(this);" id="9" value="9" class="btn btn-primary numbut">9</button></td>
                <td rowspan="2"><button onClick="delNum();" id="del" value="del" class="btn delbut">DEL</button></td></tr>
            <tr> <td><button onClick="addNum(this);" id="4" value="4" class="btn btn-primary numbut">4</button></td>
                <td> <button onClick="addNum(this);" id="5" value="5" class="btn btn-primary numbut">5</button></td>
                <td><button onClick="addNum(this);" id="6" value="6" class="btn btn-primary numbut">6</button></td></tr>
            <tr> <td><button onClick="addNum(this);" id="1" value="1" class="btn btn-primary numbut">1</button></td>
                <td><button onClick="addNum(this);" id="2" value="2" class="btn btn-primary numbut">2</button></td>
                <td><button onClick="addNum(this);" id="3" value="3" class="btn btn-primary numbut">3</button></td>
                <td rowspan="2"><button onClick="clearAll();" id="clear" value="clear" class="btn clrbut">CLEAR</button> </td></tr>
            <tr><td colspan="2"><button onClick="addNum(this);" id="0" value="0" class="btn btn-primary numbut zbut">0</button></td>
                <td><button  id="dot" value="." class="btn btn-primary dotbut">.</button></td></tr>
        </table>
    </div>



<script >
$(document).ready(function(){
$('#input1').focus();
});
    var input1 = document.getElementById('input1')
    var input2 = document.getElementById('input2')
    var input3 = document.getElementById('input3')
    var num0 = document.getElementById('0')
    var num1 = document.getElementById('1')
    var num2 = document.getElementById('2')
    var num3 = document.getElementById('3')
    var num4 = document.getElementById('4')
    var num5 = document.getElementById('5')
    var num6 = document.getElementById('6')
    var num7 = document.getElementById('7')
    var num8 = document.getElementById('8')
    var num9 = document.getElementById('9')
    var dot = document.getElementById('dot')

    function moveCursor(event) {
        if (event.keyCode != 8) {
            if (input1.value.length == 3 && input2.value.length < 3) {
                input2.focus();
            }
            else if (input2.value.length == 3 && input3.value.length < 3) {
                input3.focus();
            }
            else if (input3.value.length == 3) {
//                input3.blur();
                check.focus();
            }
        }
        else {
            if (input3.value.length == 0 && input2.value.length != 0) {
                input2.focus();
            }
            if (input2.value.length == 0) {
                input1.focus();
            }
        }
        updateQRcode();
    }

    function updateQRcode() {
        qr_code.value = input1.value + input2.value + input3.value;
    }

    function addNum(obj) {
        if (input1.value.length < 3) {

            input1.value += obj.value;
            updateQRcode();
        }
        else if (input2.value.length < 3) {

            input2.value += obj.value;
            updateQRcode();
        }
        else if (input3.value.length < 3) {

            input3.value += obj.value
            updateQRcode();
        }
        else {
        }
    }

    function clearAll() {

        input1.value = ""
        input2.value = ""
        input3.value = ""
        updateQRcode();
    }

    function delNum() {

        if (input3.value.length > 0) {

            input3.value = input3.value.substring(0, input3.value.length - 1);
            updateQRcode();
        }

        else if (input2.value.length > 0) {

            input2.value = input2.value.substring(0, input2.value.length - 1);
            updateQRcode();
        }
        else if (input1.value.length > 0) {

            input1.value = input1.value.substring(0, input1.value.length - 1);
            updateQRcode();
        }
        else {
        }

    }


</script><!-- ----------------------------------------------------------------------- -->
@stop