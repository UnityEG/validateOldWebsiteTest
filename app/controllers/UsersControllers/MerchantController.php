<?php

class MerchantController extends \BaseController {

    /**
     * Instance of Merchant model
     * @var object
     */
    protected $merchant_model;

    /**
     * Instance of UserController controller class
     * @var object
     */
    protected $user_controller;

    /**
     * path to logos folder of the merchants public/images/merchants/logos
     * @var string
     */
    private $merchant_logo_path;

    /**
     * URI path to logos folder of the merchants http://www.validate.com/public/images/merchants/logos
     * @var string
     */
    private static $uri_merchant_logo_path = 'images/merchant/logos';

    /**
     * path to phosots folder of the merchants public/images/merchants/photos
     * @var string
     */
    private $merchant_photo_path;

    /**
     * URI path to photos folder of the merchants http://www.validate.com/public/images/merchants/photos
     * @var string
     */
    private static $uri_merchant_photo_path = 'images/merchant/photos';

    public function __construct( Merchant $merchant, UserController $user_controller ) {
        parent::__construct();

        $this->merchant_model = $merchant;

        $this->user_controller = $user_controller;

        $this->merchant_logo_path = public_path() . '/images/merchant/logos';

//        $this->uri_merchant_logo_path = asset( 'images/merchant/logos' );

        $this->merchant_photo_path = public_path() . '/images/merchant/photos';

//        $this->uri_merchant_photo_path = asset( 'images/merchant/photos' );


        $this->beforeFilter( 'merchant_show_all', array( 'only' => array( 'index' ) ) );

        $this->beforeFilter( 'merchant_show_one', array( 'only' => array( 'show' ) ) );

        $this->beforeFilter( 'merchant_create', array( 'only' => array( 'create', 'store' ) ) );

        $this->beforeFilter( 'merchant_update', array( 'only' => array( 'edit' ) ) );

        $this->beforeFilter( 'merchant_delete', array( 'only' => array( 'destroy' ) ) );

        $this->beforeFilter( 'check_admin', array( 'only' => array( 'destroy' ) ) );

        $this->beforeFilter( 'csrf', array( 'on' => 'post', 'on' => 'put', 'on' => 'delete' ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if ( Request::ajax() ) {
            $data = $this->ajaxSearch();
//            $data = array('error'=>'there is an error');
            return Response::json( $data );
        }//if(Request::ajax())

        if ( 'franchisor' == $this->auth_user->user_type ) {
            $franchisor_object = Franchisor::where( 'user_id', '=', $this->auth_user->id )->first( array( 'id' ) );
            $group             = $this->merchant_model->where( 'franchisor_id', '=', $franchisor_object->id )->paginate( 10 );
        }//if ( 'franchisor' == $this->auth_user->user_type )
        else {
            $group = $this->merchant_model->paginate( 10 );
        }
        $merchant_logo_path  = self::$uri_merchant_logo_path;
        $merchant_photo_path = self::$uri_merchant_photo_path;
        return View::make( 'users.merchants.indexMerchant', compact( 'group', 'merchant_logo_path', 'merchant_photo_path' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data = array();

        $default_assigned_rules = array();
        foreach ( $this->merchant_default_rules as $rule_name ) {
            $rule                     = Rule::where( 'rule_name', '=', $rule_name )->first();
            $default_assigned_rules[] = $rule->id;
        }

        $data[ 'default_assigned_rules' ] = $default_assigned_rules;
        return View::make( 'users.merchants.createMerchant', compact( 'data' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $inputs = Input::all();
//        check agreement for non admin group
        if ( is_null( $this->auth_user ) || !$this->auth_user->isAdmin() ) {
            if ( !isset( $inputs[ 'agreement' ] ) || (FALSE == $inputs[ 'agreement' ]) ) {
                return Redirect::back()->withInput()->withError( 'You must accept the agreement!' );
            }//if ( !isset($inputs['agreement'])||(FALSE == $inputs[ 'agreement' ]) )
        }//if ( is_null( $this->auth_user ) || !$this->auth_user->isAdmin() )
        
        if ( isset( $inputs[ 'website' ] ) && !empty( $inputs[ 'website' ] ) ) {
            $link_regex        = '/^https?:\/\/.+/';
            $inputs[ 'website' ] = (preg_match( $link_regex, $inputs[ 'website' ] )) ? $inputs[ 'website' ] : "https://" . $inputs[ 'website' ];
        }//if ( isset( $inputs[ 'website' ] ) && !empty( $inputs[ 'website' ] ) )
        
        $validation = Validator::make( $inputs, $this->merchant_model->create_rules );

        if ( $validation->passes() ) {

//            start transaction
            DB::beginTransaction();

//        get user id for new user from users table
            $user_id = $this->user_controller->store( 'merchant' );
            if ( !is_numeric( $user_id ) ) {
                if ( is_object( $user_id ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_id );
                }
                return Redirect::back()->withInput()->with( 'error', $user_id );
            }//( !is_numeric( $user_id ) )

            $this->merchant_model->user_id        = $user_id;
            $this->merchant_model->first_name     = $inputs[ 'first_name' ];
            $this->merchant_model->last_name      = $inputs[ 'last_name' ];
            $this->merchant_model->business_name  = $inputs[ 'business_name' ];
            $this->merchant_model->trading_name   = $inputs[ 'trading_name' ];
            $this->merchant_model->industry_id    = $inputs[ 'industry_id' ];
            $this->merchant_model->region_id      = $inputs[ 'region_id' ];
            $this->merchant_model->suburb_id      = $inputs[ 'suburb_id' ];
            $this->merchant_model->postal_code_id = $inputs[ 'postal_code_id' ];
            $this->merchant_model->address1       = $inputs[ 'address1' ];
            $this->merchant_model->address2       = $inputs[ 'address2' ];
            $this->merchant_model->phone          = $inputs[ 'phone' ];
            $this->merchant_model->website        = $inputs[ 'website' ];
            $this->merchant_model->business_email = $inputs[ 'business_email' ];
            $this->merchant_model->contact_name   = $inputs[ 'contact_name' ];
            $this->merchant_model->facebook_page_id = $inputs['facebook_page_id'];
            
            if ( !is_null( $this->auth_user) && $this->auth_user->hasRule('franchisor_assign') ) {
                $this->merchant_model->franchisor_id  = $inputs[ 'franchisor_id' ];
            }//if ( !is_null( $this->auth_user) && $this->auth_user->hasRule('franchisor_assign') )
            
            if ( !is_null( $this->auth_user ) && $this->auth_user->hasRule( 'merchant_activate' ) ) {
                $this->merchant_model->active_merchant = (isset( $inputs[ 'active_merchant' ] )) ? true : false;
            }//if (!is_null($this->auth_user)&&$this->auth_user->hasRule('merchant_activate'))

            $this->merchant_model->featured = (isset( $inputs[ 'featured' ] )) ? true : false;
            $this->merchant_model->display  = (isset( $inputs[ 'display' ] )) ? true : false;

//            upload logo if exist
            $upload_logo_check = $this->uploadLogo($user_id);
            if ( is_object( $upload_logo_check ) ) {
                return Redirect::back()->withInput()->withErrors( $upload_logo_check );
            }//if ( is_object( $upload_logo_check ) )
//              upload photo if exist
            $upload_photo_check = $this->uploadPhoto($user_id);
            if ( is_object( $upload_photo_check ) ) {
                    return Redirect::back()->withInput()->withErrors( $upload_photo_check );
                }//if ( is_object( $upload_photo_check ) )

            if ( $this->merchant_model->save() ) {
                if ( isset( $inputs[ 'supplier_ids' ] ) ) {
                    $new_merchant_id = $this->merchant_model->id;

                    $new_merchant = $this->merchant_model->find( $new_merchant_id );

                    $new_merchant->supplier()->sync( $inputs[ 'supplier_ids' ] );
                }//if ( isset( $inputs[ 'supplier_ids' ] ) )
//              If we reach here, then
//              data is valid and working.
//              Commit the queries!
                DB::commit();
                $this->sendMechantCreatedEmail($this->merchant_model);
                if ( !is_null( $this->auth_user) ) {
                    return Redirect::to( 'merchant/' )->with( 'message', 'Merchant created successfully' );
                }
                return Redirect::to('login')->withMessage('your account is now setup and under review, We are verifying your details and will email you as soon as your account is enabled');
            } //( $this->merchant_model->save() )
            else {
                DB::rollBack();
                return Redirect::back()->withInput()->with( 'error', 'Error saving user!' );
            }
        }//if ( $validation->passes() )
        else {
            return Redirect::back()->withInput()->withErrors( $validation );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show( $id ) {
        $item = $this->merchant_model->findOrFail( $id );

        $data = array();

        $item_information_from_user_table = $item->user()->first();

        $item_info_franchisor = $item->franchisor()->first();

        $active_logo = $item_information_from_user_table->userPic()->where( 'type', '=', 'logo' )->where( 'active_pic', '=', 1 )->first( array( 'pic', 'extension' ) );

        $active_photo = $item_information_from_user_table->userPic()->where( 'type', '=', 'photo' )->where( 'active_pic', '=', 1 )->first( array( 'pic', 'extension' ) );

//        buildint Suppliers list
        $item_info_supplier = $item->supplier;
        $suppliers_list     = "<ul>";
        foreach ( $item_info_supplier as $supplier ) {
            $suppliers_list .= "<li>";
            $suppliers_list .= link_to_route( 'supplier.show', $supplier->first_name, array( $supplier->id ) );
            $suppliers_list .= "</li>";
        }
        $suppliers_list .= "</ul>";

//        building Merchant Managers list
        $item_info_merchant_managers = $item->merchantManagers;

        $merchant_managers_list = "<ul>";
        foreach ( $item_info_merchant_managers as $merchant_manager ) {
            $merchant_managers_list .="<li>";
            $merchant_managers_list .= link_to_route( 'merchant_manager.show', ucfirst( $merchant_manager->first_name ) . ' ' . $merchant_manager->last_name, array( $merchant_manager->id ) );
            $merchant_managers_list .="</li>";
        }
        $merchant_managers_list.="</ul>";

        $item_logo = (is_object( $active_logo )) ? asset(self::$uri_merchant_logo_path . '/' . $active_logo->pic . '.' . $active_logo->extension) : '';

        $item_photo = (is_object( $active_photo )) ? asset(self::$uri_merchant_photo_path . '/' . $active_photo->pic . '.' . $active_photo->extension) : '';

        $data[ 'item_logo' ] = $item_logo;

        $data[ 'item_photo' ] = $item_photo;

        return View::make( 'users.merchants.showMerchant', array( 'item' => $item, 'item_information_from_user_table' => $item_information_from_user_table, 'item_info_franchisor' => $item_info_franchisor, 'merchant_managers_list' => $merchant_managers_list, 'suppliers_list' => $suppliers_list, 'data' => $data ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id ) {
        $item = $this->merchant_model->findOrFail( $id );

        $data = array();

        $item_information_from_user_table = $item->user()->first();

        $logos = $item_information_from_user_table->userPic()->where( 'type', '=', 'logo' )->get();

        $photos = $item_information_from_user_table->userPic()->where( 'type', '=', 'photo' )->get();

        $item_info_supplier = $item->supplier;
        $supplier_ids       = array();
        foreach ( $item_info_supplier as $supplier ) {
            $supplier_ids[] = $supplier->id;
        }

        $item_rules = $item_information_from_user_table->rules;

        $assigned_rules_ids = array();

        foreach ( $item_rules as $rule ) {
            if ( !is_null( $rule->pivot->active_rule ) && 0 != $rule->pivot->active_rule ) {
                $assigned_rules_ids[] = $rule->id;
            }
        }
        $data[ 'assigned_rules_ids' ] = $assigned_rules_ids;

        $data[ 'logos' ] = $logos;

        $data[ 'uri_merchant_logo_path' ] = asset(self::$uri_merchant_logo_path);

        $data[ 'photos' ] = $photos;

        $data[ 'uri_merchant_photo_path' ] = asset(self::$uri_merchant_photo_path);

        return View::make( 'users.merchants.editMerchant', array( 'item' => $item, 'item_information_from_user_table' => $item_information_from_user_table, 'supplier_ids' => $supplier_ids, 'data' => $data ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id ) {

        $merchant = $this->merchant_model->findOrFail( $id );

//        check if authenticated user is not in admin group that is the account owner
        if ( !$this->check_admin ) {
            if ( $this->auth_user->id != $merchant->user_id ) {
                return Redirect::back()->withError( 'Error with permission' );
            }//if ( $this->auth_user->id != $merchant->user_id )
        }//if ( !$this->check_admin )

        $inputs = Input::all();
        
//        Merchant's website URL
        if ( isset( $inputs[ 'website' ] ) && !empty( $inputs[ 'website' ] ) ) {
            $link_regex        = '/^https?:\/\/.+/';
            $inputs[ 'website' ] = (preg_match( $link_regex, $inputs[ 'website' ] )) ? $inputs[ 'website' ] : "https://" . $inputs[ 'website' ];
        }//if ( isset( $inputs[ 'website' ] ) && !empty( $inputs[ 'website' ] ) )

        $validation = Validator::make( $inputs, $this->merchant_model->update_rules );

        if ( $validation->passes() ) {

//        get user id for new user from users table
            $user_update = $this->user_controller->update( $merchant->user_id, 'merchant' );
            if ( $user_update != 'success' ) {
                if ( is_object( $user_update ) ) {
                    return Redirect::back()->withInput()->withErrors( $user_update );
                }
                return Redirect::back()->withInput()->with( 'error', $user_update );
            }

            $merchant->first_name     = $inputs[ 'first_name' ];
            $merchant->last_name      = $inputs[ 'last_name' ];
            $merchant->business_name  = $inputs[ 'business_name' ];
            $merchant->trading_name   = $inputs[ 'trading_name' ];
            $merchant->industry_id    = $inputs[ 'industry_id' ];
            $merchant->region_id      = $inputs[ 'region_id' ];
            $merchant->suburb_id      = $inputs[ 'suburb_id' ];
            $merchant->postal_code_id = $inputs[ 'postal_code_id' ];
            $merchant->address1       = $inputs[ 'address1' ];
            $merchant->address2       = $inputs[ 'address2' ];
            $merchant->phone          = $inputs[ 'phone' ];
            $merchant->website        = $inputs[ 'website' ];
            $merchant->business_email = $inputs[ 'business_email' ];
            $merchant->contact_name   = $inputs[ 'contact_name' ];
            $merchant->facebook_page_id = $inputs['facebook_page_id'];

            if ( $this->auth_user->hasRule('franchisor_assign') ) {
                $merchant->franchisor_id  = $inputs[ 'franchisor_id' ];
            }//if ( $this->auth_user->hasRule('franchisor_assign') )
            
            $send_activation_email = FALSE;
            if ( $this->auth_user->hasRule( 'merchant_activate' ) ) {
                if ( isset( $inputs[ 'active_merchant' ]) && (true == $inputs[ 'active_merchant' ])) {
                    $merchant->active_merchant = true;
                    $send_activation_email = TRUE;
                }//isset($inputs['active_merchant'])&&(true==$inputs['active_merchant']))
                else{
                    $merchant->active_merchant = false;
                }
            }//if ( $this->auth_user->hasRule('merchant_activate') )

            $merchant->featured = (isset( $inputs[ 'featured' ] )) ? true : false;
            $merchant->display  = (isset( $inputs[ 'display' ] )) ? true : false;

//            dealing with pictures
            $user_pic = new UserPicController();
//            activate and deactivate logos
            if ( isset( $inputs[ 'active_logo' ] ) ) {
                $logos = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'logo' )->get();
                foreach ( $logos as $logo ) {
                    $logo->active_pic = 0;
                    $logo->save();
                }//foreach ( $logos as $logo)
                $logo_to_active             = UserPic::find( $inputs[ 'active_logo' ] );
                $logo_to_active->active_pic = 1;
                $logo_to_active->save();
            }//if ( isset( $inputs[ 'active_logo' ] ) )

            if ( isset( $inputs[ 'delete_logo' ] ) ) {
                foreach ( $inputs[ 'delete_logo' ] as $logo_id ) {
                    $user_pic->deleteImage( $logo_id, $this->merchant_logo_path );
                }//foreach ( $inputs['delete_logo'] as $logo_id)
            }//if ( isset($inputs['delete_logo']) )
//            uploading logos if exist
            $upload_logo_check = $this->uploadLogo($merchant->user_id);
            if ( is_object( $upload_logo_check ) ) {
                return Redirect::back()->withInput()->withErrors( $upload_logo_check );
            }//if ( is_object( $upload_logo_check ) )
            //            activate and deactivate photos
            if ( isset( $inputs[ 'active_photo' ] ) ) {
                $photos = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'photo' )->get();
                foreach ( $photos as $photo ) {
                    $photo->active_pic = 0;
                    $photo->save();
                }//foreach ( $photos as $photo)
                $photo_to_active             = UserPic::find( $inputs[ 'active_photo' ] );
                $photo_to_active->active_pic = 1;
                $photo_to_active->save();
            }//if ( isset( $inputs[ 'active_photo' ] ) )

            if ( isset( $inputs[ 'delete_photo' ] ) ) {
                foreach ( $inputs[ 'delete_photo' ] as $photo_id ) {
                    $user_pic->deleteImage( $photo_id, $this->merchant_photo_path );
                }//foreach ( $inputs['delete_photo'] as $photo_id)
            }//if ( isset($inputs['delete_photo']) )
//            uploading photos if exist
            $upload_photo_check = $this->uploadPhoto($merchant->user_id);
            if ( $upload_photo_check != true ) {
                    return Redirect::back()->withInput()->withErrors( $upload_photo_check );
                }//if ( is_object( $upload_photo_check ) )
//            supplier table update
            if ( isset( $inputs[ 'supplier_ids' ] ) ) {
                $merchant->supplier()->sync( $inputs[ 'supplier_ids' ] );
            }//if(isset($inputs['supplier_ids']))

            if ( $merchant->save() ) {
                if ( $send_activation_email ) {
                    $this->activationEmail($merchant);
                }//if ( $send_activation_email )
                return Redirect::route( 'merchant.show', $merchant->id )->with( 'message', 'Merchant ' . $merchant->name . ' has been updated successfully' );
            } //( $merchant->save() )
            else {
                return Redirect::back()->withInput()->with( 'error', 'Error saving user!' );
            }
        }//if ( $validation->passes() )
        else {
            return Redirect::back()->withInput()->withErrors( $validation );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id ) {
        $merchant = $this->merchant_model->findOrFail( $id );

//        start transaction
        DB::beginTransaction();

        $user_delete = $this->user_controller->destroy( $merchant->user_id );
        if ( $user_delete != 'success' ) {
            return Redirect::back()->with( 'error', $user_delete );
        }

//        delete related suppliers relationship from pivot table
        $merchant->supplier()->sync( array() );

//        delete logos and photos
        $user_pic = new UserPicController();

        $logos = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'logo' )->get( array( 'id' ) );

        foreach ( $logos as $logo ) {
            $user_pic->deleteImage( $logo->id, $this->merchant_logo_path );
        }//foreach($logos as $logo)

        $photos = UserPic::where( 'user_id', '=', $merchant->user_id )->where( 'type', '=', 'photo' )->get( array( 'id' ) );

        foreach ( $photos as $photo ) {
            $user_pic->deleteImage( $photo->id, $this->merchant_photo_path );
        }//foreach ($photos as $photo)

        if ( $merchant->delete() ) {
            DB::commit();
            return Redirect::to( '/' )->with( 'message', 'Merchant ' . $merchant->name . ' has been deleted!' );
        } else {
            DB::rollBack();
            return Redirect::back()->with( 'error', 'Sorry, could not delete merchant' );
        }
    }
    
    /**
     * show Merchants logo gallery
     * @return object
     */
    public function partners( ) {
        $data = array();
        
        $merchants = Merchant::orderByRaw('Rand()')->get();
        
        $data['merchants'] = $merchants;
        
        $data['logo_path'] = asset(self::$uri_merchant_logo_path);
        
        return View::make('users.merchants.partners', compact( 'data'));
    }

//    Helper Methods

    /**
     * Ajax Search helper method
     * @return array $data
     */
    private function ajaxSearch() {
        $inputs        = Input::all();
        $data          = array();
        $search_result = '';

//        if user is franchisor
        if ( 'franchisor' == $this->auth_user->user_type ) {
            $franchisor_object = Franchisor::where( 'user_id', '=', $this->auth_user->id )->first( array( 'id' ) );
            $check             = $this->merchant_model
                    ->where( 'franchisor_id', '=', $franchisor_object->id )
                    ->where( function($query) {
                        $inputs = Input::all();
                        $query->where( 'first_name', 'like', '%' . $inputs[ 'search' ] . '%' )
                        ->orWhere( 'last_name', 'like', '%' . $inputs[ 'search' ] . '%' );
                    } )
                    ->exists();

            $merchants = $this->merchant_model
                    ->where( 'franchisor_id', '=', $franchisor_object->id )
                    ->where( function($query) {
                        $inputs = Input::all();
                        $query->where( 'first_name', 'like', '%' . $inputs[ 'search' ] . '%' )
                        ->orWhere( 'last_name', 'like', '%' . $inputs[ 'search' ] . '%' );
                    } )
                    ->get( array( 'id', 'first_name', 'last_name' ) );
        }//if ( 'franchisor' == $this->auth_user->user_type )
        else {
            $check = $this->merchant_model->where( 'first_name', 'LIKE', '%' . $inputs[ 'search' ] . '%' )->orWhere( 'last_name', 'LIKE', '%' . $inputs[ 'search' ] . '%' )->exists();

            $merchants = $this->merchant_model->where( 'first_name', 'LIKE', '%' . $inputs[ 'search' ] . '%' )->orWhere( 'last_name', 'LIKE', '%' . $inputs[ 'search' ] . '%' )->get( array( 'id', 'first_name', 'last_name' ) );
        }
        if ( $check ) {
            foreach ( $merchants as $merchant ) {
                $search_result .= '<div>' . link_to_route( 'merchant.show', $merchant->first_name . ' ' . $merchant->last_name, array( $merchant->id ) ) . '</div>';
            }//foreach ( $merchants->get(array('id', 'first_name', 'last_name')) as $merchant)
        }//if ( $merchants->exists() )
        else {
            $data[ 'error' ] = '<div>Merchant not found!</div>';
        }


        $data[ 'search_result' ] = $search_result;

        return $data;
    }
    
    /**
     * upload logos helper
     * @param integer $user_id
     * @return object | boolean
     */
    private function uploadLogo($user_id) {
        $user_pic_controller = new UserPicController();

        for ( $i = 0; $i < 2; $i++ ) {
            if ( Input::hasFile( 'logo' . $i ) ) {
                $active_pic          = ($i == 0) ? 1 : 0;
                $upload_logo_options = array(
                    'user_id'    => $user_id,
                    'image'      => Input::file( 'logo' . $i ),
                    'path'       => $this->merchant_logo_path,
                    'type'       => 'logo',
                    'active_pic' => $active_pic
                );
                return $user_pic_controller->saveImage( $upload_logo_options );
            }//if ( Input::hasFile('logo') )
        }//for ($i = 1; $i <= 2; $i++ )
    }
    
    /**
     * upload photos helper
     * @param integer $user_id
     * @return object | boolean
     */
    private function uploadPhoto($user_id) {
        $user_pic_controller = new UserPicController();
        for ( $i = 0; $i < 5; $i++ ) {
            if ( Input::hasFile( 'photo' . $i ) ) {
                $active_pic         = ($i == 0) ? 1 : 0;
                $upload_photo_array = array(
                    'user_id'    => $user_id,
                    'image'      => Input::file( 'photo' . $i ),
                    'path'       => $this->merchant_photo_path,
                    'type'       => 'photo',
                    'active_pic' => $active_pic
                );
                $check_upload = $user_pic_controller->saveImage( $upload_photo_array );
                if ( $check_upload != true ) {
                    return $check_upload;
                }//if ( $check_upload != true )
            }//if ( Input::hasFile( 'photo' . $i ) )
        }//for($i = 1; $i <= 5; $i++)
        return TRUE;
    }
    
    /**
     * get logo or photo URI
     * @param string $type
     * @return string
     */
    public static function getMerchantImagePath( $type="logo" ) {
        $prop_name = 'uri_merchant_' . $type . '_path';
        return self::$$prop_name;
    }
    
    /**
     * Send activation Email to the merchant after activation his account
     * @param object $merchant_object
     */
    private function activationEmail($merchant_object) {
        $data = array('merchant'=>$merchant_object);
        Mail::send('users.merchants.merchantActivationEmail', $data, function($message) use ($data){
            extract($data);
            $message->to($merchant->user->email)->subject('Account Activation');
        });
    }
    
    /**
     *  Send mail to merchant@validate.co.nz after creating new merchant
     * @param object $created_merchant_object
     */
    private function sendMechantCreatedEmail( $created_merchant_object ) {
        $data = array('created_merchant'=>$created_merchant_object);
        
        Mail::send('users.merchants.merchantCreateEmail', $data, function($message) use ($data){
            extract($data);
            
            $message->to('merchant@validate.co.nz')->subject('Merchant Created');
        });
    }

}
