<?php

class GiftVouchersParameterController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $controllerTitle = 'Gift Voucher Parameter';

    public function index() {
        // Public no need Authorization Check
        //if(!g::has($this->controllerTitle . ' - List')) return g::back();
        // return 'You are in GiftVouchersParameters.index';
        $data = array();

        if (Input::get('merchant_id')) {
            $merchant_id = Input::get('merchant_id');
            $merchant_business_name = Merchant::find($merchant_id)->business_name;
            $GiftVouchersParameters = GiftVouchersParameter::where('MerchantID', '=', $merchant_id)->orderBy('id', 'desc')->paginate(6);
            $GiftVouchersParameters->appends(array('merchant_id' => $merchant_id));
            $data['merchant_gift_vouchers'] = $GiftVouchersParameters;
            $data['merchant_business_name'] = $merchant_business_name;
            return View::make('GiftVouchersParameters.index', compact('GiftVouchersParameters', 'data'));
        }//if ( Input::get('merchant_id') )
        else {
            $GiftVouchersParameters = GiftVouchersParameter::orderBy('id', 'desc')->paginate(6);
        }
        return View::make('GiftVouchersParameters.index', compact('GiftVouchersParameters', 'data'));
    }

    /**
     * show merchant's gift vouchers gallery
     * @return object
     */
    public function gallery() {
        $data = array();

        if (Input::get('merchant_id')) {
            $merchant_id = Input::get('merchant_id');
            $merchant = Merchant::find($merchant_id);
            $merchant_logo = UserPic::where('user_id', '=', $merchant->user_id)->where('type', '=', 'logo')->where('active_pic', '=', 1)->first(array('pic', 'extension'));

            $merchant_photos = UserPic::where('user_id', '=', $merchant->user_id)->where('type', '=', 'photo')->get(array('pic', 'extension'));

            $merchant_suburb_object = Postcode::where('id', $merchant->suburb_id)->first(array('suburb'));
            $merchant_suburb = (!is_null($merchant_suburb_object)) ? $merchant_suburb_object->suburb : '';

            $merchant_region_object = Region::where('id', $merchant->region_id)->first(array('region'));
            $merchant_region = (!is_null($merchant_region_object)) ? $merchant_region_object->region : '';

            $GiftVouchersParameters = GiftVouchersParameter::where('MerchantID', '=', $merchant_id)->paginate(6);
            $GiftVouchersParameters->appends(array('merchant_id' => $merchant_id));
            $data['merchant'] = $merchant;
            $data['merchant_logo'] = $merchant_logo;
            $data['merchant_photos'] = $merchant_photos;
            $data['merchant_suburb'] = $merchant_suburb;
            $data['merchant_region'] = $merchant_region;
            $data['merchant_gift_vouchers'] = $GiftVouchersParameters;
            $data['default_image_path'] = UserPicController::$defaultImagesPath;
            $data['merchant_photo_path'] = MerchantController::getMerchantImagePath('photo');
            $data['merchant_logo_path'] = MerchantController::getMerchantImagePath('logo');
            return View::make('GiftVouchersParameters.singleMerchantVouchers', compact('data'));
        }//if ( Input::get('merchant_id') )
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Create'))
            return g::back();
        //
        $data = array();

        $default_voucher_image = UserPic::where('type', '=', 'default_gift_voucher_image')->first();

        $default_voucher_image_path = UserPicController::$defaultImagesPath;

//        $merchant_photo_path = MerchantController::getMerchantImagePath( 'photo' );

        $src = asset($default_voucher_image_path . '/' . $default_voucher_image->pic . '.' . $default_voucher_image->extension);

        $data['default_voucher_image'] = $default_voucher_image;
//        $data[ 'merchant_photo_path' ]        = $merchant_photo_path;
        $data['default_voucher_image_path'] = $default_voucher_image_path;
        $data['src'] = $src;
        // Set selected_terms to use it for TermsOfUse DualListBox
        $data ['selected_terms'] = null;
        //

        return View::make('GiftVouchersParameters.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {

        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Create'))
            return g::back();
        //
        $input = Input::all(); // except('xxx'); // only('xxx');
        // Set value of zero for CHECKBOXES if they were unchecked
        $input['LimitedQuantity'] = (isset($input['LimitedQuantity'])) ? : 0;
        $input['NoOfUses'] = (isset($input['NoOfUses']) && $input['NoOfUses'] == 0) ? null : $input['NoOfUses'];
        // 
        // Convert TermsOfUse array of ids to string, pass it to input, unset temp input
        if (isset($input['selected_terms'])) {
            $input['TermsOfUse'] = implode(',', $input['selected_terms']);
            unset($input['selected_terms']);
        }
        //
        /*
          if(!isset($input['Expires'])){
          $input['Expires'] 		= 0;
          $input['ValidFor'] 		= null;
          $input['ValidForUnits']	= null;
          }
         */
        // Custom validation rolls
        $messages = array(
            'greater_than' => 'The :attribute field must be greater than min val.',
            'greater_than_one' => 'The :attribute field must be greater than one.',
            'greater_than_zero' => 'The :attribute field must be greater than zero.',
            'min' => 'Minimum value for a Gift Voucher is $20.00',
        );

        $validation = Validator::make($input, GiftVouchersParameter::$rules, $messages);
        $validation->sometimes('NoOfUses', 'greater_than_one', function($input) {
            return $input->SingleUse == 0;
        });
        $validation->sometimes('ValidFor', 'required|greater_than_zero', function($input) {
            return true;
            return $input->Expires == 1;
        });
        $validation->sometimes('Quantity', 'required|greater_than_zero', function($input) {
            return $input->LimitedQuantity == 1;
        });

        if ($validation->passes()) {
            GiftVouchersParameter::create($input);
            return Redirect::route('GiftVouchersParameters.index');
        }
        return Redirect::route('GiftVouchersParameters.create')
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {


        // Public no need Authorization Check
        //if(!g::has($this->controllerTitle . ' - Show')) return g::back();
        //
        $GiftVouchersParameter = GiftVouchersParameter::find($id);
        if (is_null($GiftVouchersParameter)) {
            return Redirect::route('GiftVouchersParameters.index');
        }

        $data = array();


        $src = '';

        $voucher_image = $GiftVouchersParameter->userPic;

        $default_voucher_photo_path = UserPicController::$defaultImagesPath;

        if (!is_null($voucher_image) && ('default_gift_voucher_image' == $voucher_image->type)) {

            $src = asset($default_voucher_photo_path . '/' . $voucher_image->pic . '.' . $voucher_image->extension);
        }//if ( !is_null( $voucher_image ) )
        else {
            $default_voucher_image = UserPic::where('type', 'default_gift_voucher_image')->first(array('pic', 'extension'));

            $src = asset($default_voucher_photo_path . '/' . $default_voucher_image->pic . '.' . $default_voucher_image->extension);
        }

        $data['src'] = $src;
        // get Gift Vouchers Parameter Terms Of Use
        $x = UseTerm::whereIn('id', explode(',', $GiftVouchersParameter->TermsOfUse))->orderBy('list_order', 'asc')->get(array('name'))->toArray();
        $terms = array();
        foreach ($x as $y) {
            $terms[] = $y['name'];
        }
        $terms = implode(' &bull; ', $terms);
        $data['terms'] = $terms;
        //


        $merchant_id = $GiftVouchersParameter->MerchantID;
        $merchant = Merchant::find($merchant_id);
        $merchant_logo = UserPic::where('user_id', '=', $merchant->user_id)->where('type', '=', 'logo')->where('active_pic', '=', 1)->first(array('pic', 'extension'));
        $merchant_photos = UserPic::where('user_id', '=', $merchant->user_id)->where('type', '=', 'photo')->get(array('pic', 'extension'));
        $merchant_suburb_object = Postcode::where('id', $merchant->suburb_id)->first(array('suburb'));
        $merchant_suburb = (!is_null($merchant_suburb_object)) ? $merchant_suburb_object->suburb : '';

        $merchant_region_object = Region::where('id', $merchant->region_id)->first(array('region'));
        $merchant_region = (!is_null($merchant_region_object)) ? $merchant_region_object->region : '';

        $GiftVouchersParameters = GiftVouchersParameter::where('MerchantID', '=', $merchant_id)->paginate(6);
        $GiftVouchersParameters->appends(array('merchant_id' => $merchant_id));
        $data['merchant'] = $merchant;
        $data['merchant_logo'] = $merchant_logo;
        $data['merchant_photos'] = $merchant_photos;
        $data['merchant_suburb'] = $merchant_suburb;
        $data['merchant_region'] = $merchant_region;
        $data['merchant_gift_voucher'] = $GiftVouchersParameters;
        $data['default_image_path'] = UserPicController::$defaultImagesPath;
        $data['merchant_photo_path'] = MerchantController::getMerchantImagePath('photo');
        $data['merchant_logo_path'] = MerchantController::getMerchantImagePath('logo');




        return View::make('GiftVouchersParameters.show', compact('GiftVouchersParameter', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Edit'))
            return g::back();
        //

        $data = array();

        $GiftVouchersParameter = GiftVouchersParameter::find($id);
        if (is_null($GiftVouchersParameter)) {
            return Redirect::route('GiftVouchersParameters.index');
        }

        $default_voucher_image = UserPic::where('type', '=', 'default_gift_voucher_image')->first();

        $current_voucher_image = $GiftVouchersParameter->userPic;

//        $merchant_photo_path = MerchantController::getMerchantImagePath( 'photo' );

        $default_voucher_image_path = UserPicController::$defaultImagesPath;

        $src = $src = asset($default_voucher_image_path . '/' . $default_voucher_image->pic . '.' . $default_voucher_image->extension);

        if (!is_null($current_voucher_image)) {
            $src = asset($default_voucher_image_path . '/' . $current_voucher_image->pic . '.' . $current_voucher_image->extension);
        }//if(!is_null( $current_voucher_image))

        $data['default_voucher_image'] = $default_voucher_image;
        $data['current_voucher_image'] = $current_voucher_image;
//        $data[ 'merchant_photo_path' ]        = $merchant_photo_path;
        $data['default_voucher_image_path'] = $default_voucher_image_path;
        $data['src'] = $src;
        // Get TermsOfUse string, convert it to array of ids, pass it view
        $x = (isset($GiftVouchersParameter->TermsOfUse)) ? $GiftVouchersParameter->TermsOfUse : null;
        $data ['selected_terms'] = explode(',', $x);
//        print_r($GiftVouchersParameter->TermsOfUse); echo '<br />';
//        print_r($x); echo '<br />';
//        print_r($data ['selected_terms']); echo '<br />';
//        dd();
        //
        return View::make('GiftVouchersParameters.edit', compact('GiftVouchersParameter', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Edit'))
            return g::back();
        //
        $input = Input::all();
//dd($input);
        // Set value of zero for CHECKBOXES if they were unchecked
        $input['LimitedQuantity'] = (isset($input['LimitedQuantity'])) ? : 0;
        $input['NoOfUses'] = (isset($input['NoOfUses']) && $input['NoOfUses'] == 0) ? null : $input['NoOfUses'];
        // 
        // Convert TermsOfUse array of ids to string, pass it to input, unset temp input
        if (isset($input['selected_terms'])) {
            $input['TermsOfUse'] = implode(',', $input['selected_terms']);
            unset($input['selected_terms']);
        } else {
            $input['TermsOfUse'] = '';
        }
//dd($input);
        //
        /*
          if(!isset($input['Expires'])){
          $input['Expires'] 		= 0;
          $input['ValidFor'] 		= null;
          $input['ValidForUnits']	= null;
          }
         */
        // Custom validation rolls
        $messages = array(
            'greater_than' => 'The :attribute field must be greater than min val.',
            'greater_than_one' => 'The :attribute field must be greater than one.',
            'greater_than_zero' => 'The :attribute field must be greater than zero.',
        );

        $validation = Validator::make($input, GiftVouchersParameter::$rules, $messages);
        $validation->sometimes('NoOfUses', 'greater_than_one', function($input) {
            return $input->SingleUse == 0;
        });
        $validation->sometimes('ValidFor', 'required|greater_than_zero', function($input) {
            return true;
            return $input->Expires == 1;
        });
        $validation->sometimes('Quantity', 'required|greater_than_zero', function($input) {
            return $input->LimitedQuantity == 1;
        });

        if ($validation->passes()) {
            $GiftVouchersParameter = GiftVouchersParameter::find($id);
            $GiftVouchersParameter->update($input);
            return Redirect::route('GiftVouchersParameters.show', $id);
        }
        return Redirect::route('GiftVouchersParameters.edit', $id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        // Authorization Check =================================================
        $rule_name = $this->controllerTitle . ' - Delete';
        if (!g::has($rule_name)) {
            return g::backForRule($rule_name);
        }
        // if Authorization Check ok go on -------------------------------------
        //
        GiftVouchersParameter::find($id)->delete();
        return Redirect::route('GiftVouchersParameters.index');
    }

//    Helper Methods
    public function ajaxSearch() {
        $input = Input::get('search');

        $data = array();

        $search_result = '';

        $merchant_check = Merchant::where('business_name', 'like', '%' . $input . '%')->orWhere('first_name', 'like', '%' . $input . '%')->orWhere('last_name', 'like', '%' . $input . '%')->exists();

        if ($merchant_check) {
            $merchants = Merchant::where('business_name', 'like', '%' . $input . '%')->orWhere('first_name', 'like', '%' . $input . '%')->orWhere('last_name', 'like', '%' . $input . '%')->get(array('id', 'business_name'));

            foreach ($merchants as $merchant) {
                $search_result .= "<div>" . link_to_route('GiftVouchersParameters.index', $merchant->business_name, array('merchant_id' => $merchant->id)) . "</div>";
            }//foreach ( $merchants as $merchant)

            $data['search_result'] = $search_result;
        }//if ( $check )
        else {
            $data['error'] = "<div>No Merchants found!</div>";
        }

        return Response::json($data);
    }

}
