<?php

class CartController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
        if (Session::get('PaymentStatus') == 'success') {
            //
            $this->createGiftVoucherAndClearCart();
        }
        //
        return View::make('Cart.index');
    }

    public function createGiftVoucherAndClearCart() {
        //
        ini_set('max_execution_time', 180 * Cart::count());
        //
        foreach (Cart::content() as $row) {
            $voucher_data = (array) $row->options;
            $voucher_data = $voucher_data[chr(0) . '*' . chr(0) . 'items'];
            $voucher_data['qr_code'] = GiftVoucherController::generateVoucherCode();
            //
            $gv = GiftVoucher::create($voucher_data);
//echo '<pre>';
//dd($gv);
            // send Mail To Customer
            GiftVoucherController::sendMailToCustomer($gv->id);
            // TODO: Send recipet to customer
        }
        //
        Session::forget('PaymentStatus');
        //
        Cart::destroy();
        //
        return View::make('Cart.index');
        //
    }

    public function AddedToCart() {
        //
        return View::make('Cart.AddedToCart');
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
        Cart::destroy();
        return Redirect::route('Cart.index')->with('message', 'Cart Cleared.');
    }

}
