<?php

class GiftVoucherController extends \BaseController {

    private $route = 'GiftVoucher';
    private $controllerTitle = 'Gift Voucher';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - List'))
            return g::back();
        // return 'You are in GiftVouchers.index';
        $group = GiftVoucher::orderBy('id', 'desc')->paginate(6);
        return View::make($this->route . '.index', compact('group'));
    }

    public function myVault() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - List'))
            return g::back();
        // return 'You are in GiftVouchers.index';
        $group = GiftVoucher::where('status', 1)->paginate(6);
        return View::make($this->route . '.myVault', compact('group'));
    }

    public function vaultHistory() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - List'))
            return g::back();
        // return 'You are in GiftVouchers.index';
        $group = GiftVoucher::where('status', 2)->paginate(6);
        return View::make($this->route . '.vaultHistory', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($gift_vouchers_parameters_id) {
        #########################################################
        if (!is_object(Auth::user())) {
            return g::login();
        }
        if (!Auth::user()->hasCustomerAccount()) {
            return Redirect::to('customer/create?user_id=' . Auth::user()->id)->with('message', 'Please complete your customer profile first.');
        }
        #########################################################
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Create'))
            return g::back();
        //
        $customer_id = Input::get('customer_id');
        $gift_vouchers_parameters = (GiftVouchersParameter::find($gift_vouchers_parameters_id));
        //
        $MerchantBusinessName = (Merchant::find($gift_vouchers_parameters->MerchantID)->business_name);
        $gift_vouchers_parameters_MinVal = ($gift_vouchers_parameters->MinVal);
        $gift_vouchers_parameters_MaxVal = ($gift_vouchers_parameters->MaxVal);
        $gift_vouchers_parameters_Title = ($gift_vouchers_parameters->Title);
        //
        return View::make($this->route . '.create')
                        ->with('MerchantBusinessName', $MerchantBusinessName)
                        ->with('customer_id', $customer_id)
                        ->with('gift_vouchers_parameters', $gift_vouchers_parameters);
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
        $input['customer_id'] = Auth::user()->customer->id; 
        $input['qr_code'] = $this->generateQRcode();
        //
        $gvp = GiftVouchersParameter::find($input['gift_vouchers_parameters_id']);
        //
//        echo '<pre>';
//        $customer = Auth::user()->customer;
//        dd($gvp);
//        dd($input);
//        dd($customer->id);
//        dd($customer);
//        dd($customer->getName());
        //
        $rules = array(
            'qr_code' => 'required|numeric|unique:giftvoucher',
            'voucher_value' => 'required|numeric|between:' . $gvp->MinVal . ',' . $gvp->MaxVal,
            'delivery_date' => 'date_format:d/m/Y',
            'recipient_email' => 'required|email',
        );

        $validation = Validator::make($input, $rules);
        //
        if (!$validation->passes()) {
            return Redirect::route($this->route . '.create', array($input['gift_vouchers_parameters_id']))
                            ->withInput()
                            ->withErrors($validation)
                            ->with('message', 'There were validation errors.'); //, array('user' => 1)
        }
        // Convert local time to UTC time in order to save it in DB
        $input['delivery_date'] = g::utcDateTime($input['delivery_date'] . ' 00:00:00', 'd/m/Y H:i:s');
        $input['expiry_date'] = $input['delivery_date']->copy();
        switch ($gvp->ValidForUnits) {
            case 'd':
                $input['expiry_date']->addDays($gvp->ValidFor);
                break;
            case 'w':
                $input['expiry_date']->addWeeks($gvp->ValidFor);
                break;
            case 'm':
                $input['expiry_date']->addMonths($gvp->ValidFor);
                break;
            default:
        } // switch
        // -1 second
        $input['expiry_date'] = $input['expiry_date']->subSeconds(1); // toDateTimeString();
        $input['status'] = 1;
        $input['voucher_balance'] = $input['voucher_value'];
        //
        // --------------------------------------- End of Gathering Voucher Data
        //
        // Add to Cart =========================================================
        Cart::add($input['qr_code'], $gvp->Title, 1, $input['voucher_value'], $input);
        //
        return Redirect::route('Cart.AddedToCart')->with('message', 'Voucher added to Cart.');
        //

        dd();
        //
        GiftVoucher::create($input);
        //
        /*
          echo $gvp->merchant->business_name;
          echo $customer->name;
          dd($input);
         */

        // Send Data for email
        $data = array(
            'qr_code' => $input['qr_code'],
            'delivery_date' => $input['delivery_date'],
            'expiry_date' => $input['expiry_date'],
            'recipient_email' => $input['recipient_email'],
            'voucher_value' => $input['voucher_value'],
            'merchant_business_name' => $gvp->merchant->business_name,
            'merchant_business_address1' => $gvp->merchant->business_address1,
            'merchant_business_phone' => $gvp->merchant->business_phone,
            'merchant_business_website' => $gvp->merchant->business_website,
            'customer_name' => $customer->getName(),
        );
        // Send Mail
        //return View::make($this->route.'.mail')->with($data);

        Mail::send($this->route . '.mail', $data, function($message) use ($data) {
            //dd($data);
            $message->to($data['recipient_email'], $data['customer_name'])->subject('Voucher Purchased');
            //$message->attach('voucher/qrcodepng/'. $data['qr_code'] .'.png');
        });


        return Redirect::route($this->route . '.index');
    }

    public function generateQRcode() {
        $qr_code = '3' . mt_rand(00000001, 99999999); // better than rand()
        //$qr_code = 377634481; // for testing
        // call the same function if the barcode exists already
        if (GiftVoucher::where('qr_code', '=', $qr_code)->exists()) {
            //echo '('.strlen($qr_code).') Ext: ' . $qr_code . '      ';
            return $this->generateQRcode();
        }
        if (strlen($qr_code) < 9) {
            //echo '('.strlen($qr_code).') Les: ' . $qr_code . '      ';
            return $this->generateQRcode();
        }
        // otherwise, it's valid and can be used
        //echo '('.strlen($qr_code).') new: ';
        return $qr_code;
    }

    public static function generateVoucherCode() {
        $qr_code = '3' . mt_rand(00000001, 99999999); // better than rand()
        //$qr_code = 377634481; // for testing
        // call the same function if the barcode exists already
        if (GiftVoucher::where('qr_code', '=', $qr_code)->exists()) {
            //echo '('.strlen($qr_code).') Ext: ' . $qr_code . '      ';
            return GiftVoucherController::generateVoucherCode();
        }
        if (strlen($qr_code) < 9) {
            //echo '('.strlen($qr_code).') Les: ' . $qr_code . '      ';
            return GiftVoucherController::generateVoucherCode();
        }
        // otherwise, it's valid and can be used
        //echo '('.strlen($qr_code).') new: ';
        return $qr_code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        // Check and update Voucher status before show it
        $this->check($id);
        //
        $item = GiftVoucher::find($id);
        if (is_null($item)) {
            return Redirect::route($this->route . '.index');
        }
        //
        // Get Voucher log =====================================================
        $group = GiftVoucherValidation::where('giftvoucher_id', '=', $item->id)->get();
        return View::make($this->route . '.show', compact('item', 'group'));
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
        $GiftVoucher = GiftVoucher::find($id);
        if (is_null($GiftVoucher)) {
            return Redirect::route($this->route . '.index');
        }
        return View::make($this->route . '.edit', compact('GiftVoucher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Update'))
            return g::back();
        //
        $input = Input::all();

        // Set value of zero for CHECKBOXES if they were unchecked
        $input['LimitedQuantity'] = (isset($input['LimitedQuantity'])) ? : 0;
        $input['NoOfUses'] = (isset($input['NoOfUses']) && $input['NoOfUses'] == 0) ? null : $input['NoOfUses'];
        if (!isset($input['Expires'])) {
            $input['Expires'] = 0;
            $input['ValidFor'] = null;
            $input['ValidForUnits'] = null;
        }

        // Custom validation rolls
        $messages = array(
            'greater_than' => 'The :attribute field must be greater than min val.',
            'greater_than_one' => 'The :attribute field must be greater than one.',
            'greater_than_zero' => 'The :attribute field must be greater than zero.',
        );

        $validation = Validator::make($input, GiftVoucher::$rules, $messages);
        $validation->sometimes('NoOfUses', 'greater_than_one', function($input) {
            return $input->SingleUse == 0;
        });
        $validation->sometimes('ValidFor', 'required|greater_than_zero', function($input) {
            return $input->Expires == 1;
        });
        $validation->sometimes('Quantity', 'required|greater_than_zero', function($input) {
            return $input->LimitedQuantity == 1;
        });

        if ($validation->passes()) {
            $GiftVoucher = GiftVoucher::find($id);
            $GiftVoucher->update($input);
            return Redirect::route($this->route . '.show', $id);
        }
        return Redirect::route($this->route . '.edit', $id)
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
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Delete'))
            return g::back();
        //
        GiftVoucher::find($id)->delete();
        return Redirect::route($this->route . '.index');
    }

    public function search() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Check'))
            return g::back();
        //		
        return View::make($this->route . '.search');
    }

    public function result() {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Check'))
            return g::back();
        //		
        $input = Input::all(); // all() // except('xxx'); // only('xxx');

        $rules = array('qr_code' => 'required|numeric|digits:9');
        $messages = array(
            'required' => 'Voucher Code is required.',
            'numeric' => 'Voucher Code must be a number.',
            'digits' => 'Voucher Code must be 9 digits.',
        );
        $validation = Validator::make($input, $rules, $messages);
        if (!$validation->passes()) {
            return Redirect::route($this->route . '.search')
                            ->withInput()
                            ->withErrors($validation)
                            ->with('message', 'There were some errors.');
        }

        $item = GiftVoucher::where('qr_code', '=', $input['qr_code'])->first();
        if (is_null($item)) {
            Session::flash('message', 'There is no Gift Voucher with this code: ' . $input['qr_code']);
            return Redirect::route($this->route . '.search');
        }
        //
        // Get Voucher log =====================================================
        $group = GiftVoucherValidation::where('giftvoucher_id', '=', $item->id)->get();
        return View::make($this->route . '.show', compact('item', 'group'));
//        return View::make($this->route . '.show', compact('item'));
    }

    public function status($id) {
        // Authorization Check
        if (!g::has($this->controllerTitle . ' - Validate'))
            return g::back();
        //
        // Update Gift Voucher =============================================
        // Gathering data to update Gift Voucher
        $input = Input::all(); // all() // except('xxx'); // only('xxx');
        $input['validation_date'] = date("Y-m-d H:i:s");
        //
        $GiftVoucher = GiftVoucher::find($id);
        // Update Gift Voucher
        $GiftVoucher->update($input);
        // 
        // Create Gift Voucher Validation Log ==================================
        // Gathering data
        $input_log = array();
        $input_log['giftvoucher_id'] = $id;
        $input_log['user_id'] = Auth::user()->id;
        $input_log['date'] = $input['validation_date'];
        $input_log['value'] = $GiftVoucher->voucher_value;
        $input_log['balance'] = $GiftVoucher->voucher_balance;
        $input_log['log'] = 'Validated';
        //
        // Create Validation Log
        GiftVoucherValidation::create($input_log);
// TODO test this

        return Redirect::route($this->route . '.show', $id);
    }

    public function check($id) {
        //
        $item = GiftVoucher::find($id);
        if (is_null($item)) {
            return Redirect::route($this->route . '.index');
        }
        // If Voucher isn't valid no need to check it ==========================
        if ($item->status != 1) { // NOT Valid, 1 = Valid
            return false;
        } // ---------------------------------------------- If it is VALID go on
        /*
          echo '<h1>Voucher</h1>';
          echo '<pre>';
          print_r($item);
          echo '</pre>';
          echo '<h1>Voucher Parameters</h1>';
          echo '<pre>';
          print_r($item->parameter);
          echo '</pre>';
         */
        $input = array();
        //
        // Number of Uses Check ================================================
        if ($item->parameter->NoOfUses != null) {
            if ($item->used_times >= $item->parameter->NoOfUses) {
                $input['status'] = 0;
            }
        }
        //
        // Expiry Date Check ===================================================
        if (date("Y-m-d H:i:s") >= $item->expiry_date) {
            $input['status'] = 0;
        }
        //
        // Update Gift Voucher =================================================
        if (count($input) > 0) {
            $item->update($input);
        }
        return true;
    }

    public function sendMailTest_2BD($gv_id) {
        // 
        $gv = GiftVoucher::find($gv_id);
        //
        $m_active_logo = $gv->parameter->merchant->user->userPic()
                ->where('type', '=', 'logo')
                ->where('active_pic', '=', 1)
                ->first(array('pic', 'extension'));
        $m_logo_filename = (is_object($m_active_logo)) ? 'images/merchant/logos/' . $m_active_logo->pic . '.' . $m_active_logo->extension : 'voucher/images/validate_logo.png';
        //
        // Gathering Data for email
        $data = array(
            'm_logo_filename' => $m_logo_filename,
            'qr_code' => $gv->qr_code,
            'delivery_date' => $gv->delivery_date,
            'expiry_date' => $gv->expiry_date,
            'voucher_value' => $gv->voucher_value,
            'merchant_business_name' => $gv->parameter->merchant->business_name,
            'voucher_title' => $gv->parameter->Title,
            'TermsOfUse' => $gv->parameter->TermsOfUse,
            'merchant_business_address1' => $gv->parameter->merchant->address1,
            'merchant_business_phone' => $gv->parameter->merchant->phone,
            'merchant_business_website' => $gv->parameter->merchant->website,
            'recipient_email' => $gv->recipient_email,
            'customer_name' => $gv->customer->getName(),
//            'recipient_email' => Input::get('email'), // for Testing
//            'customer_name' => Input::get('customer_name'), // for Testing
        );
//        dd($data['recipient_email'] . ' /*/ ' . $data['customer_name']);
        //
        // =============================================================================
        // Generate virtual voucher image 
        $voucher_filename = g::voucher($data);
        // 
        // =============================================================================
        // Send Mail with virtual voucher image attached
        ini_set('max_execution_time', 180);
        Mail::send('GiftVoucher.mailTest', $data, function($message) use ($data, $voucher_filename) {
            //dd($data);
            $message->to($data['recipient_email'], $data['customer_name'])->subject('Voucher Purchased');
            //$message->to('shadymag@gmail.com', 'customer_name')->subject('Voucher Purchased'); // for Testing
            $message->attach($voucher_filename);
        });
        // =============================================================================
        // Render img as HTML img tag
        // encode image to data:image/png;base64,
        $img = Image::make($voucher_filename);
        $img->encode('data-url');
        echo '<img style="" src="' . $img . '" alt="' . $data['qr_code'] . '" />';
        //
        if (file_exists($voucher_filename)) {
            unlink($voucher_filename);
        }
        // For testing
        //return View::make($this->route . '.mailTest')->with($data);
    }

    public static function getDataForEmail($gv) {
        //
        // Gathering Data for email
        $m_active_logo = $gv->parameter->merchant->user->userPic()
                ->where('type', '=', 'logo')
                ->where('active_pic', '=', 1)
                ->first(array('pic', 'extension'));
        $m_logo_filename = (is_object($m_active_logo)) ? 'images/merchant/logos/' . $m_active_logo->pic . '.' . $m_active_logo->extension : 'voucher/images/validate_logo.png';
        // get Gift Vouchers Parameter Terms Of Use
        $x = UseTerm::whereIn('id', explode(',', $gv->parameter->TermsOfUse))->orderBy('list_order', 'asc')->get(array('name'))->toArray();
        $terms = array();
        foreach ($x as $y) {
            $terms[] = $y['name'];
        }
        $TermsOfUse = implode(' ● ', $terms);
        //
        //
        $data = array(
            'm_logo_filename' => $m_logo_filename,
            'qr_code' => $gv->qr_code,
            'delivery_date' => $gv->delivery_date,
            'expiry_date' => $gv->expiry_date,
            'voucher_value' => $gv->voucher_value,
            'merchant_business_name' => $gv->parameter->merchant->business_name,
            'voucher_title' => $gv->parameter->Title,
            'TermsOfUse' => $TermsOfUse,
            'merchant_business_address1' => $gv->parameter->merchant->address1,
            'merchant_business_phone' => $gv->parameter->merchant->phone,
            'merchant_business_website' => $gv->parameter->merchant->website,
            'recipient_email' => $gv->recipient_email,
            'customer_name' => $gv->customer->getName(),
            'customer_email' => $gv->customer->user->email,
        );
        //
        return $data;
    }

    public static function generateVirtualVoucher($id) {
        //
        // get Gift Voucher
        $gv = GiftVoucher::find($id);
        //
        // Gathering Data for email
        $data = GiftVoucherController::getDataForEmail($gv);
        //
        // Generate virtual voucher image 
        $voucher_filename = g::voucher($data);
        //
        // Add $voucher_filename to $data array
        $data['voucher_filename'] = $voucher_filename;
        //
        return $data;
    }

    public function showVirtualVoucher($id) {
        //
        // Generate Virtual Voucher
        $data = $this->generateVirtualVoucher($id);
        // extract $data array as variables
        extract($data);
        // make virtual voucher image
        $img = Image::make($voucher_filename);
        // encode image to data:image/png;base64,
        $img->encode('data-url');
        // Render img as HTML img tag
        echo '<img style="" src="' . $img . '" alt="' . $qr_code . '" />';
        //
        // For security delete virtual voucher file after use it
        $this->unlinkVirtualVoucher($voucher_filename);
    }

    public static function unlinkVirtualVoucher($voucher_filename) {
        //
        // For security delete virtual voucher file after use it
        if (file_exists($voucher_filename)) {
            unlink($voucher_filename);
        }
    }

    public static function sendMailToBeneficiary($gv_id) {
        //
        $MailBodyView = 'GiftVoucher.mailToBeneficiaryBody';
        //
        GiftVoucherController::sendMail($gv_id, $MailBodyView, false);
    }

    public static function sendMailToCustomer($gv_id) {
        //
        $MailBodyView = 'GiftVoucher.mailToCustomerBody';
        //
        GiftVoucherController::sendMail($gv_id, $MailBodyView, true);
    }

    public static function sendMail($gv_id, $MailBodyView, $MailToCustomer = false) {
        //
        // Generate Virtual Voucher
        $data = GiftVoucherController::generateVirtualVoucher($gv_id);
        //
        if ($MailToCustomer) {
            // Case Customer
            $data['email_to_email'] = $data['customer_email'];
            $data['email_to_name'] = $data['customer_name'];
        } else {
            // Case Beneficiary
            $data['email_to_email'] = $data['recipient_email'];
            $data['email_to_name'] = $data['recipient_email'];
        }
//        echo '<pre>';
//        dd($data);
        // extract $data array as variables
        extract($data);
        // 
        if (ini_get('max_execution_time') < 180) {
            ini_set('max_execution_time', 180);
        }
        // Send Mail with virtual voucher image attached
        Mail::queue($MailBodyView, $data, function($message) use ($data, $voucher_filename) {
            //
            $message->to($data['email_to_email'], $data['email_to_name'])->subject('Validate Voucher');
            //$message->to('shadymag@gmail.com', 'customer_name')->subject('Voucher Purchased'); // for Testing
            $message->attach($voucher_filename);
        });
        // 
        // For security delete virtual voucher file after use it
        GiftVoucherController::unlinkVirtualVoucher($voucher_filename);
    }

    public static function sendMail_old_no_need($gv) {
        /*
          echo '<hr />';
          echo '<pre>';
          print_r($gv);
          print_r($gv->parameter);
          echo '</pre>';
          dd();
         */
        // Gathering Data for email
        $data = array(
            'qr_code' => $gv->qr_code,
            'delivery_date' => $gv->delivery_date,
            'expiry_date' => $gv->expiry_date,
            'recipient_email' => $gv->recipient_email,
            'voucher_value' => $gv->voucher_value,
            'merchant_business_name' => $gv->parameter->merchant->business_name,
            'voucher_title' => $gv->parameter->Title,
            'TermsOfUse' => $gv->parameter->TermsOfUse,
            'merchant_business_address1' => $gv->parameter->merchant->address1,
            'merchant_business_phone' => $gv->parameter->merchant->phone,
            'merchant_business_website' => $gv->parameter->merchant->website,
            'customer_name' => $gv->customer->getName(),
        );
        // For testing
        //return View::make($this->route.'.mail')->with($data);
        //
        // Send Mail
        Mail::send('GiftVoucher.mail', $data, function($message) use ($data) {
            //dd($data);
            $message->to($data['recipient_email'], $data['customer_name'])->subject('Voucher Purchased');
            //$message->attach('voucher/qrcodepng/'. $data['qr_code'] .'.png');
        });
    }

}
