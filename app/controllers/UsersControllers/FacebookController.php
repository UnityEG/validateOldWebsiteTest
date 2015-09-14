<?php

class FacebookController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $merchant_gift_vouchers = GiftVouchersParameter::get();
        
        $data['merchant_gift_vouchers'] = $merchant_gift_vouchers;
        
        $data['merchant_photo_path'] = MerchantController::getMerchantImagePath('photo');
        
        return View::make('users.facebook.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function store() {
        
        $data = array();

        $signed_request = Input::get( 'signed_request' );

        list($encoded_sig, $payload) = explode( '.', $signed_request, 2 );

        $secret = "2135a089ca04b049c00e7866ccba87a2"; // Use your app secret here
        // decode the data
        $sig  = $this->base64_url_decode( $encoded_sig );
        $decoded_json = json_decode( $this->base64_url_decode( $payload ), true );

        // confirm the signature
        $expected_sig = hash_hmac( 'sha256', $payload, $secret, $raw          = true );
        if ( $sig !== $expected_sig ) {
            error_log( 'Bad Signed JSON signature!' );
            return null;
        }
        
        $facebook_page_id = $decoded_json[ 'page' ][ 'id' ];
        
        if ( !isset($decoded_json['user_id']) || empty($decoded_json['user_id']) ) {
            return 'please login with your facebook account! <div class="info">if you already logged in to facebook please make sure that the "Apps, Websites and Plugins" in settings is enabled.</div>';
        }//if ( empty($facebook_user_id) )
        
        $facebook_user_id = $decoded_json['user_id'];
        
        $data['facebook_user_id'] = $facebook_user_id;
        $data = array_merge($data, $this->gallery($facebook_page_id));
        
        return View::make('users.facebook.index', compact('data'));
    }
    
    /**
     * vouchers gallery of facebook merchant's page
     * @param integer $facebook_page_id
     * @return array
     */
    public function gallery( $facebook_page_id) {
        $data = array();
        $merchant = Merchant::where('facebook_page_id', '=', $facebook_page_id)->first();
        
        $merchant_logo = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'logo' )->where( 'active_pic', '=', 1 )->first( array( 'pic', 'extension' ) );

            $merchant_photos = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'photo' )->get( array( 'pic', 'extension' ) );

            $merchant_suburb_object = Postcode::where( 'id', $merchant->suburb_id )->first( array( 'suburb' ) );
            $merchant_suburb        = (!is_null( $merchant_suburb_object )) ? $merchant_suburb_object->suburb : '';

            $merchant_region_object = Region::where( 'id', $merchant->region_id )->first( array( 'region' ) );
            $merchant_region        = (!is_null( $merchant_region_object )) ? $merchant_region_object->region : '';

            $GiftVouchersParameters           = GiftVouchersParameter::where( 'MerchantID', '=', $merchant->id )->get();
            $data[ 'merchant' ]               = $merchant;
            $data[ 'merchant_logo' ]          = $merchant_logo;
            $data[ 'merchant_photos' ]        = $merchant_photos;
            $data[ 'merchant_suburb' ]          = $merchant_suburb;
            $data[ 'merchant_region' ]          = $merchant_region;
            $data[ 'merchant_gift_vouchers' ] = $GiftVouchersParameters;
            $data[ 'default_image_path' ]     = UserPicController::$defaultImagesPath;
            $data[ 'merchant_photo_path' ]    = MerchantController::getMerchantImagePath( 'photo' );
            $data[ 'merchant_logo_path' ]     = MerchantController::getMerchantImagePath( 'logo' );
            
            return $data;
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function show( $id) {
        $facebook_user_id = Input::get('facebook_user_id');
        
        $customer = Customer::where('facebook_user_id', $facebook_user_id)->first();
        
        if ( is_null( $customer ) ) {
            return 'Sorry you don\'t have customer account on '.link_to_route('myHome', 'Validate ', null, array('target'=>'_blank')).'<br>You can create a customer account from '.  link_to_route( 'customer.create', 'here', null, array('target'=>'_blank')).'.';
        }
        
//        if user logged in Vlidate log him out first
        Auth::logout();
        
//        log customer in from facebook account
        Auth::login($customer->user);
        
        if ( Auth::user()->hasCustomerAccount() !== TRUE) {
            return 'Sorry, Your account on '.link_to_route('myHome', 'Validate', null, array('target'=>'_blank')).' is suspended please contact to help center.';
        }//if ( Auth::user()->hasCustomerAccount() !== TRUE)
        
        
        return '<a href="'.  secure_url( 'GiftVoucher/create?customer_id='.$customer->id. '&gift_vouchers_parameters_id='.$id ).'" target="_blank">Continue Purchasing</a>';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id ) {
        //
    }

//    Helper Methods
    
    /**
     * base64 decoding
     * @param string $input
     * @return string
     */
    function base64_url_decode( $input ) {
        return base64_decode( strtr( $input, '-_', '+/' ) );
    }
}
