<?php $route = 'GiftVoucher';?>
@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif

<div align="right">{{ link_to_route($route.'.index', 'List all Gift Vouchers') }}</div>
<h1>Check Gift Voucher</h1>
<p>&nbsp;</p>
<!--
{{ Form::open(array('route' => $route.'.result', 'role' => 'form')) }}
	<div class="form-group">
		{{ Form::label('qr_code', 'Voucher Code:') }}
		{{ Form::text ('qr_code', null, array('class'=>'form-control', 'id'=>'qr_code')) }}
	</div>

	{{ Form::submit('Check', array('class' => 'btn btn-primary')) }}
{{ Form::close() }}
-->
<!-- ----------------------------------------------------------------------- -->
<!-- <link href="bootstrap.css" rel="stylesheet"/> -->

 <style>
.numbut{padding:28px 45px; background-color: #000;}
.nums{max-width:450px; border:1px solid #ddd; padding: 10px 20px; text-align:center; font-size: 16px;}
.nums input[type="text"]{text-align:center;}
.num{max-width:135px; height:45px; font-size:20px;}
.checkbut{padding:28px 40px; background-color: #000; width:430px;}
.zbut{width:209px;}
.dotbut{padding:28px 47px; background-color: #000;}
.delbut,.clrbut{height:158px; width:105px; background-color:#F0F0F0 !important;}
table tr td { padding:2px;}


 </style>
 <center>


        <div class="nums">
            <label > Enter Voucher Code </label> <br />
            <div class="input-group">
            <input onkeyup="moveCursor(event)"   id="input1" type="text" class="form-control num" placeholder="">
            <input onkeyup="moveCursor(event)"   id="input2" type="text" class="form-control num" placeholder="">
            <input onkeyup="moveCursor(event)"   id="input3" type="text" class="form-control num" placeholder="">
        </div>
        </div>
<br /><br />
<table>
        <tr> <td colspan="4">
			{{ Form::open(array('route' => $route.'.result', 'role' => 'form')) }}
				<div class="form-group" style="display: none;">
					{{ Form::label('qr_code', 'Voucher Code:') }}
					{{ Form::text ('qr_code', null, array('class'=>'form-control', 'id'=>'qr_code')) }}
				</div>
				<button type="submit" id="check" value="check" class="btn btn-primary checkbut">CHECK</button>
			{{ Form::close() }}
		</td></tr>
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



</center>

<script >
var input1=document.getElementById('input1')
var input2=document.getElementById('input2')
var input3=document.getElementById('input3')
var num0=document.getElementById('0')
var num1=document.getElementById('1')
var num2=document.getElementById('2')
var num3=document.getElementById('3')
var num4=document.getElementById('4')
var num5=document.getElementById('5')
var num6=document.getElementById('6')
var num7=document.getElementById('7')
var num8=document.getElementById('8')
var num9=document.getElementById('9')
var dot=document.getElementById('dot')

function moveCursor(event){
if(event.keyCode != 8 ) {
if(input1.value.length == 3 && input2.value.length<3 ){
input2.focus();
}
else if (input2.value.length == 3 && input3.value.length < 3){
input3.focus();
}
else if (input3.value.length == 3){
input3.blur();
}
}
else{
if(input3.value.length == 0 && input2.value.length != 0){
input2.focus();
}
if(input2.value.length == 0){
input1.focus();
}
}
}

function updateQRcode() {
	qr_code.value = input1.value+input2.value+input3.value;
}

function addNum (obj) {
if ( input1.value.length < 3 ){

    input1.value+=obj.value;
    updateQRcode();
}
else if ( input2.value.length < 3 ){

    input2.value+=obj.value;
    updateQRcode();
}
else if ( input3.value.length < 3 ){

    input3.value+=obj.value
    updateQRcode();
}
else {}
}

function clearAll() {

    input1.value=""
    input2.value=""
    input3.value=""
    updateQRcode();
}

function delNum() {

if ( input3.value.length > 0 ){

    input3.value=input3.value.substring(0, input3.value.length-1);
    updateQRcode();
}

else if ( input2.value.length > 0 ){

    input2.value=input2.value.substring(0, input2.value.length-1);
    updateQRcode();
}
else if ( input1.value.length > 0 ){

   input1.value=input1.value.substring(0, input1.value.length-1);
    updateQRcode();
}
else {}

}


</script><!-- ----------------------------------------------------------------------- -->
@stop