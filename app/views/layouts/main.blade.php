<?php
if ( !is_null( Auth::user() ) ) {
    $user = Auth::user();
    if ( is_object( $user ) && !empty( $user ) ) {
        $type      = $user->user_type;
        $user_info = $user->$type()->first();
        $active_column = 'active_'.$type;
        if ( (1 !== (int)$user_info->$active_column) ) {
            $not_activate_message = "Sorry your account has not yet been activated. We are verifying your details and will email you as soon as your account is enabled";
        }
    }//if(is_object($user) && !empty($user))
}// if($user = Auth::user())
?>
<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>VALIDATE</title>

        <!-- Bootstrap -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/corporate.css') }}" rel="stylesheet">
        <link rel='stylesheet' id='ing-font-standard-css'  href='https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,400,400italic,700,700italic,900,900italic&#038;subset=latin,latin-ext' type='text/css' media='all' />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="{{ URL::asset('css/social-buttons.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/mynav.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/corporate-light.css') }}" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <!-- jQuery (necessary for AutoComplete ComboBox) -->
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>


        <!-- Format Currency Files -->

        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type="text/javascript" src="{{ URL::asset('js/jquery.formatCurrency.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/scrolling-nav.js') }}"></script>

        <style>

            #logo-link:hover{
                animation-name: logoAnimate;
                animation-duration: 0.5s;
            }

            @keyframes logoAnimate {
                0%{transform: scale(1,1);}
                50%{transform: scale(0.9,0.9);}
                100% {transform: scale(1,1);}
            }

            .list-table tbody tr td ul{
                white-space:nowrap !important;
                display:inline-block !important;
                list-style:none !important;
            }
			
			.custom-nav li a{
				padding:30px 0px;
			}
			
			@media(min-width:1000px){
				.dash-cont{
					width:71.5%;
					margin-left:-4.5%;
				}
				.canvasjs-chart-canvas{
					width:97%;
					margin-left:2%;
				}
			}
			
			
			.navbar{
			background-color:#ccc !important;
			}
			.navbar-default .navbar-nav > li > a{
			color: #333 !important;
			}
			.nav {
			margin-right: 58px !important;
			}
			.ing-footer{
			background-color: #ccc !important;
			}
			.h-widget{
			color:#000 !important;
			}
			.widget.widget_text ul li a{
			color:#24485A !important;
			}
			.forms-back{
			padding-top:15px !important;
			}
			.forms-back h2{
			font-size:25px !important;
			}

        </style>

    </head>
    <body >



        <!--TOP NAVBAR-->
        <nav style="z-index:9999999" class="  navbar navbar-default navbar-fixed-top top-nav-collapse" role="navigation">
            <div  class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="pull-left" href="{{ URL::to('home') }}" ><img id="logo-link"  src="{{ URL::asset('images/logo.png') }}" /></a>

                @if(!(isset($type) && ( $type=='owner' || $type=='admin' || $type=='franchisor' || $type=='merchant' || $type=='developer')))
                <form  class="pull-left form-inline" role="search">
                    <div class="input-group" >
                        <input  type="text" class="form-control " placeholder="Industry" name="srch-term" id="srch-term">
                        <input type="text" class="form-control" placeholder="City" name="srch-term" id="srch-term">
                        <div class="input-group-btn" style="float:left;">
                            <button id="search-btn"  class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                </form>
                @endif	

            </div>


            <div  class="collapse navbar-collapse navbar-ex1-collapse pull-right">
                <ul class="nav navbar-nav">
                    <li >
                        <a class="page-scroll" href="{{ URL::to('home') }}">Home</a>
                    </li>
                    <li>
                        <a  class="" href="{{route('merchant.partners')}}">Validate Partners</a>
                    </li>

                    @if(isset($type) && isset($user_info))

                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Vouchers <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>{{ link_to_route('GiftVouchersParameters.create', 'Create Gift Vouchers') }}</li>
                            <li>{{ link_to_route('GiftVouchersParameters.index', 'List All Gift Vouchers') }}</li>
                            <li role="separator" class="divider"></li>
                            <li>{{ link_to_route('GiftVoucher.search', 'Check Gift Vouchers') }}</li>
                            <li>{{ link_to_route('GiftVoucher.index', 'List Purchased Gift Vouchers') }}</li>
                            <li role="separator" class="divider"></li>
                            <li>{{link_to('DealVouchersParameters/create', 'Create Deal Vouchers')}}</li>
                        </ul>
                    </li>
                    {{-- <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>{{ link_to('Industrys', 'Industries') }}</li>
                    <li>{{ link_to('UseTerms', 'Terms of Use') }}</li> 
                </ul>
                </li>		 --}}
                <li><a  href="{{ URL::to('Cart') }}">Add To Cart</a></li>
                <li><a  href="{{ URL::to('myvault') }}"><span  class="glyphicon"></span> Vault</a></li>		

                @include('layouts.menus.'.$type.'Menu')
                @elseif(Session::has('facebook_user'))
                @include('layouts.menus.facebookUserMenu')

                <li><a  href="{{ URL::to('Cart') }}">Add To Cart</a></li>

                @else
                <li><a  href="{{ URL::to('login') }}">Login</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Sign Up<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>{{link_to_route('customer.create', 'Customer')}}</li>
                        <li>{{link_to_route('merchant.create', 'Merchant')}}</li>
                    </ul>
                </li>
                @endif	

                </ul>
            </div>
        </nav> 


        @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
        @elseif(Session::has('error'))
        <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        
        @if(isset($not_activate_message) && !empty($not_activate_message))
        <div class="alert alert-danger">{{$not_activate_message}}</div>
        @endif
        
        <div class="row">
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                @if(isset($user) && !empty($type))
				 <div style="padding:0; margin:0; margin-left:-1%;" class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                @include('layouts.sidebars.'.$type.'Sidebar')
				 </div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 dash-cont">
					@yield('main')
				</div>
				@else
				<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                @yield('main')
				</div>
                @endif
        </div>

        <br /><br />

        <footer class="ing-footer" role="contentinfo">
            <br />  <br />

            <div class="row">
                <div class="container" style="padding-left:17%; width:95%;">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div id="text-3" class="widget widget_text"><h4 class="h-widget">Info</h4>			<div class="textwidget"><ul>
                                    <li><a href="https://www.validate.co.nz/about-validate-vouchers/">About Validate</a></li>
                                    <li><a href="{{ URL::to('contact') }}">Contact Us</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                    <li><a href="#">FAQ's</a></li>
                                </ul></div>
                        </div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div id="text-4" class="widget widget_text"><h4 class="h-widget">Customer Info</h4>			<div class="textwidget"><ul>
                                    <li><a href="{{ URL::to('vault') }}">Customer Vault</a></li>
                                    <li><a href="#">Validate Gurantee</a></li>
                                </ul></div>
                        </div></div><div class=" col-lg-3 col-md-3 col-sm-6 col-xs-6"><div id="text-5" class="widget widget_text"><h4 class="h-widget">Merchant Info</h4>			<div class="textwidget"><ul>
                                    <li><a href="#">Join The Revolution</a></li>
                                    <li><a href="#">Refer a Business</a></li>
                                </ul></div>
                        </div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><div id="text-6" class="widget widget_text"><h4 class="h-widget">Follow Validate</h4>			<div class="textwidget"><ul id="sn-icons">
                                    <li><a href="http://www.facebook.com/validatenz">Facebook</a></li>
                                    <li><a href="http://twitter.com/validatenz">Twitter</a></li>
                                </ul></div>
                        </div></div>		

                </div>
            </div> <!-- end .ing-row-fluid -->


            <div class="footer-bottom cf">

                <div class="ing-container-fluid max width">


                    <ul style="display:inline-block !important;" id="menu-validate-menu-1" class="ing-footer-nav"><li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-26 current_page_item menu-item-29"><a href="{{ URL::to('home') }}">Home</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89"><a href="{{ URL::to('validatepartners') }}">Validate Partners</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-22"><a href="http://www.validate.co.nz/about-validate-vouchers/">About Us</a></li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-41"><a href="{{ URL::to('contact') }}">Contact Us</a></li>
                    </ul>					         
                </div>

            </div>

        </footer> 
        <script>
var labels = document.getElementsByTagName('label')
if ( labels !== 'undefined' ) {
  for (count = 0; count < labels.length; count++) {
      for (count2 = 0; count2 < labels[count].parentNode.children.length; count2++) {
          if ( labels[count].parentNode.children[count2].type == 'checkbox' || labels[count].parentNode.children[count2].type == 'radio' ) {
              labels[count].style.display = "inline"
          }
      }
  }
}
        </script>

    </body>
</html>