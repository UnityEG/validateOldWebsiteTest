@extends('layouts.main')
@section('main')	


<style>
@media(min-width:1000px){
	.carousel img{
	min-height:200px;
	max-height:200px;
	}
}

.carousel h4{
 background-color:rgba(255,255,255,0.3);
 border-radius:5px;
}

</style>
<div class=" pull-left ing-responsive-video ing-responsive-video-shortcode embed col-lg-12 col-md-12 col-sm-12 col-xs-12">
<!-- slider -->
<div  id="con" class=" pull-right ing-responsive-video ing-responsive-video-shortcode embed col-lg-5 col-md-5 col-sm-12 col-xs-12 jumborn" >
<div  id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>
 
  <!-- Wrapper for slides -->
  <div  class="carousel-inner" >
    <div  class="item active">
      <img src="{{ URL::asset('images/s1.jpg') }}" alt="...">
      <div class="carousel-caption">
          
      </div>
    </div>
    <div class="item">
      <img src="{{ URL::asset('images/s2.jpg') }}" alt="...">
      <div class="carousel-caption">
          <h4>image description</h4>
      </div>
    </div>
    <div class="item">
      <img  src="{{ URL::asset('images/s3.jpg') }}" alt="...">
      <div class="carousel-caption">
           <h4>image description</h4>
      </div>
    </div>
  </div>
 
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div> <!-- Carousel -->

<br /><br />
<div class="ing-responsive-video-inner five-by-three"><iframe src="https://player.vimeo.com/video/85220835?title=0&amp;byline=0&amp;portrait=0" allowfullscreen="allowfullscreen" frameborder="0" height="333" width="770"></iframe></div>

</div>	
<p style="text-align:justify; font-size:18px;">
Validate is a voucher generation and redemption platform.  Any business in any industry can use the platform.  Validate gives merchants complete control by allowing them to set all the parameters on a voucher and then make the vouchers available for purchase.  Any type of voucher can be created from a gift voucher, deal voucher, concession voucher, discount voucher, multi site voucher, freebie voucher, coffee card voucher the list goes on. Validate facilitates the entire process from allowing merchants to create vouchers, customers make the purchase, the sending and storing of virtual vouchers and then finally the validation process.<br /> <br /> 

 

Merchants have the flexibility to set parameters on each type of voucher then make them available for purchase through the validate website the merchants  website or their facebook page. Reporting allows merchants to measure each voucher promotion, reviewing purchases vs redemptions and redemption times. <br /> <br /> 

 

What about a franchise or merchants that want to work with another merchant. No problem validate is a smart and flexible platform. Franchise stores can sell and redeem product with reporting on where and when validations were made. Two merchants can be separate entities yet still offer a joint voucher for the likes of a game of 10 ten pin and a meal for $20.00 at the restaurant down the road. <br /> <br /> 

 

Validate is constantly adding new features giving the merchant smarter ways to promote their business and the customer greater flexibility by purchasing vouchers direct from the merchants and storing them in one handy location.
</p>
</div>
 
@stop