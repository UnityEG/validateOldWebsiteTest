<?php

class GiftVoucherValidationController extends \BaseController {

    //private $ControllerTitle; // mya b e useless
    private $RouteName;
    private $ModelClassName;
    //
    private $RulePrefix;
    private $RuleList;
    private $RuleShow;
    private $RuleEdit;
    private $RuleUpdate;
    private $RuleDelete;

    public function __construct() {
        $this->RouteName = 'GiftVoucherValidation';
        $this->ModelClassName = 'GiftVoucherValidation';
        //
        $this->RulePrefix = 'Gift Voucher Validation - ';
        $this->RuleList = $this->RulePrefix . 'List';
        $this->RuleCreate = 'Gift Voucher - Validate';
//        $this->ControllerTitle	= 'Gift Voucher'; // mya b e useless
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        // Authorization Check
        if (!g::has($this->RuleList)) {
            return g::back();
        }
        //
        $group = call_user_func($this->ModelClassName . '::paginate', 15);
        return View::make($this->RouteName . '.index', compact('group'))
                        ->with('RouteName', $this->RouteName);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        // Authorization Check =================================================
        $rule_name = $this->RuleCreate;
        if (!g::has($rule_name)) {
            return g::backForRule($rule_name);
        }
        //-------------------------------------- if Authorization Check ok go on
        //
        $giftvoucher = GiftVoucher::find(Input::get('giftvoucher_id'));
        //
        return View::make($this->RouteName . '.create')
                        ->with('RouteName', $this->RouteName)
                        ->with('giftvoucher', $giftvoucher);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        // Authorization Check
        if (!g::has($this->RuleCreate)) {
            return g::back();
        }
        // Read Input ==========================================================
        $input = Input::all(); // except('xxx'); // only('xxx');
        $giftvoucher = GiftVoucher::find($input['giftvoucher_id']);
        $input['value'] = trim($input['value'], '$');
        // Validate Input ======================================================
        $rules = array(
            'value' => 'required|numeric|greater_than_zero|max:' . $giftvoucher->voucher_balance,
        );
        //
        $messages = array(
            'max' => 'The :attribute may not be greater than ' . g::formatCurrency($giftvoucher->voucher_balance),
            'greater_than_zero' => 'The :attribute field must be greater than zero.',
        );
        //
        $validation = Validator::make($input, $rules, $messages);
        //
        if ($validation->fails()) {
            return Redirect::route($this->RouteName . '.create', array('giftvoucher_id' => $input['giftvoucher_id']))
                            ->withInput()
                            ->withErrors($validation)
                            ->with('message', 'There were validation errors.'); //, array('user' => 1)
        }// Validation is OK go on ---------------------------------------------
        // 
        // Update Gift Voucher =================================================
        // Gathering data
        $isValidated = false;
        $input_giftvoucher = array();
        $input_giftvoucher['voucher_balance'] = $giftvoucher->voucher_balance - $input['value'];
        $input_giftvoucher['used_times'] = $giftvoucher->used_times + 1;
        if ($input_giftvoucher['voucher_balance'] <= 0) {
            $isValidated = true;
        }
        if ($giftvoucher->parameter->NoOfUses != null) {
            if ($input_giftvoucher['used_times'] >= $giftvoucher->parameter->NoOfUses) {
                $isValidated = true;
            }
        }
        if ($isValidated) {
            $input_giftvoucher['status'] = 2; // Validated            
            $input['log'] = 'Validated'; 
        } else {
            $input_giftvoucher['status'] = 1; // Still Valid
            $input['log'] = 'Valid'; 
        }
        $input_giftvoucher['validation_date'] = date("Y-m-d H:i:s");
        //
        // Update Gift Voucher
        $giftvoucher->update($input_giftvoucher);
        //                         
        // Create Gift Voucher Validation Log ==================================
        // Gathering data
        $input['user_id'] = Auth::user()->id;
        $input['date'] = $input_giftvoucher['validation_date'];
        $input['balance'] = $input_giftvoucher['voucher_balance'];
        //
        // Create Validation Log
        GiftVoucherValidation::create($input);

//        echo '<pre>'; print_r($input_giftvoucher); echo '</pre>';
//        echo '<pre>'; print_r($input)            ; echo '</pre>';

        return Redirect::route('GiftVoucher.show', $input['giftvoucher_id']);
        dd();

// ==============================================================================================================================
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        $item = GiftVoucher::find($id);
        if (is_null($item)) {
            return Redirect::route($this->route . '.index');
        }
        return View::make($this->route . '.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
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
        //
        GiftVoucher::find($id)->delete();
        return Redirect::route($this->route . '.index');
    }

    public function search() {
        return View::make($this->route . '.search');
    }

    public function result() {
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
        return View::make($this->route . '.show', compact('item'));
    }

    public function status($id) {
        $input = Input::all(); // all() // except('xxx'); // only('xxx');
        //
            //dd($input);
        //
            $GiftVoucher = GiftVoucher::find($id);
        $GiftVoucher->update($input);
        return Redirect::route($this->route . '.show', $id);
    }

}
