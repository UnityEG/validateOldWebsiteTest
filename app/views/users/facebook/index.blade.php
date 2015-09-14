<?php extract( $data ); ?>
@extends('users.facebook.facebookMainLayout')

@section('main')
<style>
/*    @media (min-width: 1200px)
    {
        .voucherbox img {
            height:250px;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .voucherbox img {
            height:200px;
        }
    }

    @media (min-width: 768px) and (max-width: 992px) {
        .voucherbox img {
            height:250px;
        }
    }*/

   .voucherbox {
        overflow:hidden;		
    }

    .voucherbox img {
        width:100%;
		border-top-left-radius:5px;
		border-top-right-radius:5px;
		border:1px solid #ddd;
		border-bottom:0px;
    }


    .voucherttitle {
	border-bottom-left-radius:5px;
	border-bottom-right-radius:5px;
	padding:5px;
	border:1px solid #ddd;
	border-top:0px;
    }

    .vouchertext {
        font-weight:bold;
        font-size: 14px;
        word-wrap: break-word;
        height:80px;
		overflow:hidden;	
    }


    .vouchertext p{
        font-weight:normal;
        font-size: 14px;
    }

    .merch-thum:hover {
        transform:scale(3,3);
		-ms-transform:scale(3,3);
		-o-transform:scale(3,3);
		-webkit-transform:scale(3,3);
		-moz-transform:scale(3,3);
		z-index:999999;
    }
	
	#map iframe:hover {
        transform:scale(2,2);
		-ms-transform:scale(2,2);
		-o-transform:scale(2,2);
		-webkit-transform:scale(2,2);
		-moz-transform:scale(2,2);
		z-index:9999999;
    }
	.voucher-email{
		padding:1px 5px !important;
		font-size:12px !important;
		margin-top:-5px !important;
	}

	
</style>

<!--facebook javascript sdk-->
<div id="fb-root"></div>
<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if ( d.getElementById(id) )
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=1589058994679439";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!--end of facebook javascript sdk-->

<div class="row" style="margin:0 20px;">
    
    <div class="col-lg-1 col-md-3 col-sm-3 col-xs-12" >
        <p>{{{$merchant->business_name}}}</p>
        @if(isset($merchant_logo) && is_object($merchant_logo))
        <img src="{{asset($merchant_logo_path.'/'.$merchant_logo->pic.'.'.$merchant_logo->extension)}}" class="img-thumbnail"/>
		<p>{{{$merchant->address1}}} <br />
		 {{{$merchant_suburb}}} @if( isset($merchant_region) && isset($merchant_suburb) ),&nbsp @endif {{{$merchant_region}}} <br />
		 {{{$merchant->phone}}}
		 </p>
        @endif
    </div>
	
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" >
	<div id="map"></div>	
	</div>
	
    <div  class="col-lg-6 col-md-6 col-sm-3 col-xs-12 pull-right" style=" text-align:right; padding-top:3%;">	
        @if(isset($merchant_photos) && !is_null($merchant_photos))
        @foreach($merchant_photos as $merchant_photo)
        <img src="{{asset($merchant_photo_path.'/'.$merchant_photo->pic.'.'.$merchant_photo->extension)}}" class="img-thumbnail merch-thum" style="width:22%; margin:5px;"/>
		@endforeach
        @endif
    </div>	
</div>	
<br /><br /><br /><br />

<div class="row">
    @foreach($data['merchant_gift_vouchers'] as $voucher)
    <div style="margin:20px 20px;" class="col-lg-4 col-md-4 col-sm-5 col-xs-12  voucherbox">
        @if(is_object($voucher->userPic) && ('default_gift_voucher_image' == $voucher->userPic->type))
        <a class="voucher-link" href="{{secure_url('/facebook/'.$voucher->id.'?facebook_user_id='.$facebook_user_id)}}">
            <img src="{{asset($default_image_path.'/'.$voucher->userPic->pic.'.'.$voucher->userPic->extension)}}" />
        </a>	
        @else
        <?php $default_voucher_image = UserPic::where('type', 'default_gift_voucher_image')->first(array('pic', 'extension')); ?>
        <a class="voucher-link" href="{{secure_url('/facebook/'.$voucher->id.'?facebook_user_id='.$facebook_user_id)}}">
            <img src="{{asset($default_image_path.'/'.$default_voucher_image->pic.'.'.$default_voucher_image->extension)}}" />
        </a>
        @endif
        <div style="background-color:#fff;" class="voucherttitle">
            <div class="vouchertext">
                <a class="voucher-link" href="{{secure_url('/facebook/'.$voucher->id.'?facebook_user_id='.$facebook_user_id)}}">{{{$voucher->Title}}}</a><br />
                <p>{{$voucher->short_description}}</p>
            </div>
            <div style="padding:5px; width:100%; background-color:#f8f8f8;">
			<div class="fb-like" data-href="{{secure_url('GiftVouchersParameters/'.$voucher->id)}}" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
			 <a href="mailto:?body={{route('GiftVouchersParameters.show', array($voucher->id))}}" class="btn btn-info voucher-email">Email</a>
			</div>
	   </div>
    </div>
    @endforeach
</div>


<script>

jQuery('document').ready(function($){
    var address = "{{$merchant->address1}}";
    var embed ="<iframe  style='width:100%; height:160px; margin-top: 42px;' class='img-responsive' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent( address ) +"&amp;output=embed'></iframe>";
    $("#map").html(embed);
});




</script>

@stop