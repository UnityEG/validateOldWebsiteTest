<?php extract( $data ); ?>

@extends('layouts.main')
@section('main')
<link href="{{ URL::asset('css/flipclock.css') }}" rel="stylesheet">
<link href="{{asset('css/wysiwyg/wysiwyg.css')}}" rel="stylesheet" >
<script type="text/javascript" src="{{ URL::asset('js/flipclock.js') }}"></script>
<style>
    @media (min-width: 1200px)
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



    .voucherbox {
        overflow:hidden;	
        margin-bottom:10px;
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
	.merch-thum,#map{
	    transition:transform 0.5s;
		-ms-transition:transform 0.5s;
		-o-transition:transform 0.5s;
		-webkit-transition:transform 0.5s;
		-moz-transition:transform 0.5s;
	}
    .merch-thum:hover {
        transform:scale(3,3);
        -ms-transform:scale(3,3);
        -o-transform:scale(3,3);
        -webkit-transform:scale(3,3);
        -moz-transform:scale(3,3);
        z-index:999999;
    }

    #map:hover {
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
    .desc{
        background-color:#fff;
        padding:20px;
        border-radius:5px;
        border:1px solid #ccc;
        border-right:5px solid #999;
        margin:0px;
        margin-bottom:10px;
        text-align:left;
    }

    .v-timer{
        background-color:#fff;
        border-radius:5px;
        border:1px solid #ccc;
        margin:0px;
        margin-bottom:10px;
        text-align:center;
    }
    .v-timer p{
        font-weight:bold;
        font-size:20px;
        margin-bottom:30px;
        margin-top:20px;
    }
    .normal-desc{
        padding-top: 0px;
        padding-bottom: 20px;
        padding-left: 0px;
    }

    .term{
        font-weight: bold;
        color: black;
        text-align: justify;
    }
	
	#map{
		position: relative;
		padding-bottom: 66.8%;
		padding-top: 30px;
		height: 0px;
		overflow: hidden;
	}
	#map iframe{
		position: absolute;
		top: 37px;
		left: 0px;
		width: 100%;
		height: 100%;
	}

</style>

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

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
        <p style="font-size:20px; margin-bottom:10px;">{{{$merchant->business_name}}}</p>
        @if(isset($merchant_logo) && is_object($merchant_logo))
        <img src="{{asset($merchant_logo_path.'/'.$merchant_logo->pic.'.'.$merchant_logo->extension)}}"/>
        <p>{{{$merchant->address1}}} <br />
            {{{$merchant_suburb}}} @if( isset($merchant_region) && isset($merchant_suburb) ),&nbsp @endif {{{$merchant_region}}} <br />
            {{{$merchant->phone}}}
        </p>
        @endif
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
        <div id="map"></div>	
    </div>

    <div  class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right" style=" text-align:left; padding-top:3%;">	
        @if(isset($merchant_photos) && !is_null($merchant_photos))
        @foreach($merchant_photos as $merchant_photo)
        <img src="{{asset($merchant_photo_path.'/'.$merchant_photo->pic.'.'.$merchant_photo->extension)}}" class="merch-thum" style="width:22%; margin:5px;"/>
        @endforeach
        @endif
    </div>	
</div>	

<br />

<div class="row">

    <div style="padding:0px; margin-bottom:10px;" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pull-left">

        <?php $voucher               = $GiftVouchersParameter ?>
        <div  class=" voucherbox">
            @if(is_object($voucher->userPic) && ('default_gift_voucher_image' == $voucher->userPic->type))
            <img src="{{asset($default_image_path.'/'.$voucher->userPic->pic.'.'.$voucher->userPic->extension)}}" />
            @else
            <?php $default_voucher_image = UserPic::where( 'type', 'default_gift_voucher_image' )->first( array( 'pic', 'extension' ) ); ?>
            <img src="{{asset($default_image_path.'/'.$default_voucher_image->pic.'.'.$default_voucher_image->extension)}}" />
            @endif

            <div style="background-color:#fff;" class="voucherttitle">
                <div class="vouchertext">
                    {{{$GiftVouchersParameter->Title}}} <br />
                    <p>{{$GiftVouchersParameter->short_description}}</p>
                </div>
                <div style="padding:5px; width:100%; background-color:#f8f8f8;">
                    <div class="fb-like" data-href="{{secure_url('GiftVouchersParameters/'.$GiftVouchersParameter->id)}}" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                    <a href="mailto:?body={{rawurlencode( "Check out this ".$GiftVouchersParameter->Title." at ".$merchant->business_name.", it's available for a limited time "."\n\n". route('GiftVouchersParameters.show', array($GiftVouchersParameter->id)))}}&subject={{rawurlencode("I think you would be interested in this offer: ".$GiftVouchersParameter->Title)}}" class="btn btn-info voucher-email">Email</a>
                </div>

            </div>
        </div>

        <div class="v-timer">
            <p>Time Left To Purchase</p>
            <div class="clock"></div>
        </div>

        <a href="{{route('GiftVoucher.create', array($GiftVouchersParameter->id))}}" style="font-size:20px; font-weight:bold; padding:20px 0px; width:100%; margin-bottom:20px;" class="btn btn-success">PURCHASE</a>



    </div>





    <div style="padding:0px;" class="col-lg-7 col-md-7 col-sm-12 col-xs-12 pull-right">
        @if($GiftVouchersParameter->long_description)	
        <div class="col-md-12 normal-desc">
            {{$GiftVouchersParameter->long_description}}
        </div>
        @endif	
        @if($terms)
        <div class="col-md-12 normal-desc">
            <h3>Terms of use:</h3>
            <span class="term">{{$terms}}</span>
        </div>
        @endif	


    </div>
</div>




<script>

    jQuery('document').ready(function ($) {
        var address = "{{$merchant->address1}}";
        var embed = "<iframe  frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q=" + encodeURIComponent(address) + "&amp;output=embed'></iframe>";
        $("#map").html(embed);
    });

</script>

<?php
if ( $GiftVouchersParameter->ValidForUnits == "d" ) {
    $seconds = $GiftVouchersParameter->ValidFor * 24 * 60 * 60;
} else if ( $GiftVouchersParameter->ValidForUnits == "w" ) {
    $seconds = $GiftVouchersParameter->ValidFor * 7 * 24 * 60 * 60;
} else if ( $GiftVouchersParameter->ValidForUnits == "m" ) {
    $seconds = $GiftVouchersParameter->ValidFor * 30 * 7 * 24 * 60 * 60;
}
?>
<script type="text/javascript">
    var clock;

    $(document).ready(function () {

        clock = $('.clock').FlipClock(444444, {
            clockFace: 'DailyCounter',
            countdown: true
        });
    });
</script>

@stop