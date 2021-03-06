@extends('layouts.main')
@section('main')
	
<!-- Header End -->	
	<br><br>
  <!--
  BEGIN #top.site
  
  --> 

   
  <div id="top" class="site">  
    
<div class=" pull-left ing-responsive-video ing-responsive-video-shortcode embed col-lg-12 col-md-12 col-sm-12 col-xs-12">
<!-- slider -->
<div  id="con" class=" pull-right col-lg-5 col-md-5 col-sm-12 col-xs-12 jumborn" >


<img src="{{ URL::asset('images/vault.jpg') }}" />

</div>

<h3> Customer Vault </h3> <br /> 	
<p style="text-align:justify; font-size:18px;">

All registered users of validate have access to a personal vault where vouchers from all validate merchants are stored. The vault is a customer's virtual wallet containing all valid vouchers as well as history on past vouchers. <br /> <br />

The vault is secure and requires a customer to login with their validate registered email address and password. 
Once logged in the customer can view all valid vouchers. Each voucher has all relevant information such as purchase date, expiry date, value, voucher code and terms of use <br /> <br />

The vault removes the need to carry paper vouchers. When a customer wants to redeem a voucher they simply access the vault, select the voucher and show this to the merchant. The merchant can then enter the voucher code or scan the QR code and validate the voucher.Once validated the voucher is stamped and can no longer be used. The voucher is then moved into another section so all past vouchers can still be viewed. <br /> <br />

If the customer has push notifications enabled, the vault will notify the user they have a voucher about to expire. In addition to this they will also get an email notifying them. The vault is constantly evolving with new features in developmentexpanding on giving the consumer a greater experience. <br /> <br />
</p>
</div>	
</div>

@stop