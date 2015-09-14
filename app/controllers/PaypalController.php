<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaypalController extends \BaseController {

    private $_api_context;

    public function __construct() {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPayment() {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Read vouchers from Cart and create PayPay items =====================
        $item_array = array();
        foreach (Cart::content() as $row) {
            $item = new Item();
            $item->setName($row->name) // item name
                    ->setCurrency('USD')
                    ->setQuantity($row->qty)
                    ->setPrice($row->price); // unit price
            $item_array[] = $item;
        }
        //
        // Add PayPay items to PayPay list =====================================
        $item_list = new ItemList();
        $item_list->setItems($item_array);
        //
        // Set Amount ==========================================================
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal(Cart::total());
        //
        // Set Transaction =====================================================
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Validate transaction description goes here...');
        //
        // Set Redirections ====================================================
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status'))
                ->setCancelUrl(URL::route('payment.status'));
        //
        // Set Payment =========================================================
        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        //
        // Make Payment ========================================================
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                dd('Some error occur, sorry for inconvenient');
            }
        }
        //
        // =====================================================================
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        //
        // add payment ID to session ===========================================
        Session::put('paypal_payment_id', $payment->getId());
        //
        // =====================================================================
        if (isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }
        //
        // =====================================================================
        return Redirect::route('Cart.index')
                        ->with('error', 'Unknown Paypal error occurred');
    }

    public function getPaymentStatus() {
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');

        // clear the session payment ID
        Session::forget('paypal_payment_id');

        //if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
        if (!Input::has('PayerID') || !Input::has('token')) {
            Session::put('PaymentStatus', 'failed');
            return Redirect::route('Cart.index')
                            ->with('error', 'Payment failed');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary 
        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);
        /*
          echo '<pre>';
          print_r($result);
          echo '</pre>';
          dd(); // TODO DEBUG RESULT, remove it later
         */
        if ($result->getState() == 'approved') { // payment made
            Session::put('PaymentStatus', 'success');
            return Redirect::route('Cart.index')
                            ->with('message', 'Payment success');
        }
        Session::put('PaymentStatus', 'failed');
        return Redirect::route('Cart.index')
                        ->with('error', 'Paypal payment failed');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
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
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
