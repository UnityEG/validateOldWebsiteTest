<?php extract( $data); ?>
@extends('layouts.main')

@section('main')

<style>
@media (min-width: 1200px)
{
	.merchantbox img {
		height:150px;
	}
}

@media (min-width: 992px) and (max-width: 1199px) {
		.merchantbox img {
		height:120px;
	}
}

@media (min-width: 768px) and (max-width: 992px) {
		.merchantbox img {
		height:190px;
	}
}

.merchantbox {
    //background-color:#ffffff;
	padding:0px 10px;
	margin-bottom:20px;
	//-webkit-box-shadow: 0 8px 6px -6px  #999;
	//-moz-box-shadow: 0 8px 6px -6px  #999;
	//box-shadow: 0 8px 6px -6px #999;
	overflow:auto;
	//o-transition:box-shadow 0.5s;
	//ms-transition:box-shadow 0.5;
	//-moz--transition:box-shadow 0.5s;
	//-webkit-transition:box-shadow 0.5s;
   // transition: box-shadow 0.5s;
}

.merchantbox img {
	width:100%;
}

.merchanttitle {
	//border-top:1px solid #dadada;
	margin-top:5px;
}

.merchanttext {
	font-weight:bold;
	font-size: 14px;
	word-wrap: break-word;
	height:30px;
	padding-bottom:10px;
	text-align:center;
}

.merchant-link:hover .merchantbox{
	//-webkit-box-shadow: 0 0px 5px 2px #999;
	//-moz-box-shadow: 0 0px 5px 2px #999;;
	//box-shadow: 0 0px 5px 2px #999;
	z-index:99999;
}

</style>

<!--
<ul>
<?php 
foreach($merchants as $merchant):
    $logo = $merchant->user->userPic()->where('type', '=', 'logo')->where('active_pic', '=', 1)->first();
?>
    <li>
        <a href="{{route('GiftVouchersParameters.gallery', array('merchant_id'=>$merchant->id))}}">
        @if(is_object($logo))
        <div><img src="{{$data['logo_path'].'/'.$logo->pic.'.'.$logo->extension}}"></div>
        @else
        <p>No image available</p>

        @endif
        <h3>{{{$merchant->business_name}}}</h3>
        </a>
    </li>
    
<?php
endforeach;

?>
</ul>
-->



<?php 
foreach($merchants as $merchant):
    $logo = $merchant->user->userPic()->where('type', '=', 'logo')->where('active_pic', '=', 1)->first();
?>

<a class="merchant-link" href="{{route('GiftVouchersParameters.gallery', array('merchant_id'=>$merchant->id))}}">
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12  merchantbox">
   @if(is_object($logo)) 
    <img src="{{$data['logo_path'].'/'.$logo->pic.'.'.$logo->extension}}" class="img-responsive">
	@else
	<script type="text/javascript" src="{{ URL::asset('js/iscroll.js') }}"></script>
	<img src="{{ URL::asset('images/noimage.png') }}" class="img-responsive">
	@endif
    <div class="merchanttitle"><div class="merchanttext">{{{$merchant->business_name}}}</div></div>
</div> 
</a>
    
<?php
endforeach;
?>
@stop