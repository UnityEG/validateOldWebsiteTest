<?php
if(!Session::has('facebook_user')){
    if($user = Auth::user()){
        if(is_object($user) && !empty($user)){
        	$type = $user->user_type;
        	$user_info = $user->$type()->first();
        }//if(is_object($user) && !empty($user))
    }// if($user = Auth::user())
}//if(!Session::has('facebook_user'))
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
    </head>
    <body>

<!--TOP NAVBAR-->
 <nav class="  navbar navbar-default navbar-fixed-top" role="navigation">

       
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ URL::to('home') }}" ><img id="logo"  src="{{ URL::asset('images/logo.png') }}" /></a>
            </div>
		




                        @if(!(isset($type) && ( $type=='owner' || $type=='admin' || $type=='franchisor' || $type=='merchant' || $type=='developer')))
		<div style="margin-top:15px; " class="search-form col-md-4 col-xs-5 col-sm-6 col-lg-4 pull-left">
        <form class=" form-inline" role="search" style="margin-left:-15px;" >
          <div class="input-group" >
            <input  type="text" class="form-control col-md-12" placeholder="Industry or Business" name="srch-term" id="srch-term">
			 <input  type="text" class="form-control" placeholder="City or Region" name="srch-term" id="srch-term">
            <div class="input-group-btn" style="float:left;">
              <button id="search-btn"  class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
          </div>
        </form>
      </div>
                        @endif


            <div  class="collapse navbar-collapse navbar-ex1-collapse pull-right">
                <ul class="nav navbar-nav">
                    <li class="hidden">
                        <a  class="page-scroll" href="#page-top"></a>
                    </li>
                    <li >
                        <a class="active page-scroll" href="{{ URL::to('home') }}"><span  class=" glyphicon glyphicon-home"></span> HOME</a>
                    </li>
                    <li>
                        <a  class="" href="#"><span class="glyphicon glyphicon-ok-sign"></span> VALIDATE PARTNERS
</a>
                    </li>
                 <!--   <li>
                        <a  class="page-scroll" href="#" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-user"></span> LOGIN</a>
                    </li>
					<li>
                        <a  class="page-scroll" href="#" data-toggle="modal" data-target="#registerModal"><span class="glyphicon glyphicon-pencil"></span> REGISTER</a>
                    </li> -->
					
					                        @if(isset($type) && isset($user_info))
											
		<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Vouchers <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li>{{ link_to_route('GiftVouchersParameters.create', 'Create Gift Vouchers') }}</li>
            <li>{{ link_to_route('GiftVouchersParameters.index', 'List All Gift Vouchers') }}</li>
            <li role="separator" class="divider"></li>
            <li>{{ link_to_route('GiftVoucher.search', 'Check Gift Vouchers') }}</li>
            <li>{{ link_to_route('GiftVoucher.index', 'List Purchased Gift Vouchers') }}</li>
          </ul>
	</li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li>{{ link_to('Industrys', 'Industries') }}</li>
                                <li>{{ link_to('UseTerms', 'Terms of Use') }}</li> 
                            </ul>
        </li>		
<li><a  href="{{ URL::to('Cart') }}"><span  class="cart-btn glyphicon glyphicon-shopping-cart"></span></a></li>		
												
                        @include('layouts.menus.'.$type.'Menu')
                        @elseif(Session::has('facebook_user'))
                            @include('layouts.menus.facebookUserMenu')
					
        <li><a  href="{{ URL::to('Cart') }}"><span  class="cart-btn glyphicon glyphicon-shopping-cart"></span></a></li>
                    
                        @else
                       <!-- <li>{{link_to_route('facebook.trigger', 'Facebook Login')}}</li> -->
                        <li>{{link_to('login', 'LOGIN')}}</li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">SIGN UP <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                        <li>{{link_to_route('customer.create', 'Customer')}}</li>
                        <li>{{link_to_route('merchant.create', 'Merchant')}}</li>
                        </ul>
                    </li>
                        @endif
						
						
                </ul>
            </div>
     
	
    </nav> 

	
	<!-----------LOGIN--------------->	 

<div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          <h1 class="text-center">Login</h1>
      </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block">
            <div class="form-group">
              <input class="form-control input-lg" placeholder="Email" type="text">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" placeholder="Password" type="password">
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block">Login</button>
			<div class="checkbox pull-right">
				<label><input type="checkbox"> Remember me</label>
			</div>
			<a href="#">Reset Password</a>
            </div>
          </form>
      </div>
      <div class="modal-footer">
<button class="col-md-12 btn btn-facebook pull-left"><i class="fa fa-facebook"></i> | Login with Facebook</button>	
      </div>
  </div>
  </div>
</div>

<!-----------END LOGIN-------------> 

<!-----------Register--------------->	 

<div id="registerModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
          <h1 class="text-center">Register</h1>
      </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block">
            <div class="form-group">
              <input class="form-control input-lg" placeholder="Email" type="text">
            </div>
            <div class="form-group">
              <input class="form-control input-lg" placeholder="Password" type="password">
            </div>
			<div class="form-group">
              <input class="form-control input-lg" placeholder="Re Enter Password" type="password">
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block">Register</button>
            </div>
          </form>
      </div>
      <div class="modal-footer">
      </div>
  </div>
  </div>
</div>

<!-----------END Register------------->

       

            @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
            @elseif(Session::has('error'))
            <div class="alert alert-danger">{{Session::get('error')}}</div>
            @endif
<div class="row">
<div class="col-md-1"> </div>
<div class="col-md-10">

            @yield('main')
</div>
</div>



<br /><br />

      <footer class="ing-footer" role="contentinfo">
      
  
			        <div class="widget_wrap">
				        
				        <div class="ing-container-fluid max width">
				        
						    <div class="ing-row-fluid">
						
						      <div class="ing-span3"><div id="text-3" class="widget widget_text"><h4 class="h-widget">Info</h4>			<div class="textwidget"><ul>
<li><a href="https://www.validate.co.nz/about-validate-vouchers/">About Validate</a></li>
<li><a href="{{ URL::to('contact') }}">Contact Us</a></li>
<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Terms & Conditions</a></li>
<li><a href="#">FAQ's</a></li>
</ul></div>
		</div></div><div class="ing-span3"><div id="text-4" class="widget widget_text"><h4 class="h-widget">Customer Info</h4>			<div class="textwidget"><ul>
<li><a href="{{ URL::to('vault') }}">Customer Vault</a></li>
<li><a href="#">Validate Gurantee</a></li>
</ul></div>
		</div></div><div class="ing-span3"><div id="text-5" class="widget widget_text"><h4 class="h-widget">Merchant Info</h4>			<div class="textwidget"><ul>
<li><a href="#">Join The Revolution</a></li>
<li><a href="#">Refer a Business</a></li>
</ul></div>
		</div></div><div class="ing-span3"><div id="text-6" class="widget widget_text"><h4 class="h-widget">Follow Validate</h4>			<div class="textwidget"><ul>
<li><a href="http://www.facebook.com/validatenz">Facebook</a></li>
<li><a href="http://twitter.com/validatenz">Twitter</a></li>
</ul></div>
		</div></div>						
						    </div> <!-- end .ing-row-fluid -->
						    
				        </div> <!-- end .ing-container-fluid.max.width -->
			        
			        </div> <!-- end .widget_wrap -->
	        
	                  
			          
			        <div class="footer-bottom cf">
				         
				          <div class="ing-container-fluid max width">
				          
				          							  			
					  			    					         
					             <div class="ing-social-global"></div>				      			
					  								  			
					  			<ul id="menu-validate-menu-1" class="ing-footer-nav"><li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-26 current_page_item menu-item-29"><a href="{{ URL::to('home') }}">Home</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89"><a href="http://www.validate.co.nz/validate-partners/">Validate Partners</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-22"><a href="http://www.validate.co.nz/about-validate-vouchers/">About Us</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-41"><a href="{{ URL::to('contact') }}">Contact Us</a></li>
</ul>					         
				         </div>
			         
			         </div>
         	              
      </footer> 
	  <script>
	  var labels=document.getElementsByTagName('label')
	  if(labels !== 'undefined'){
		for(count=0;count<labels.length;count++){
			for(count2=0;count2<labels[count].parentNode.children.length;count2++){
				if(labels[count].parentNode.children[count2].type=='checkbox' || labels[count].parentNode.children[count2].type=='radio'){
					labels[count].style.display="inline"
				}
			}	
		}
	  }
	  </script>
  
    </body>
</html>