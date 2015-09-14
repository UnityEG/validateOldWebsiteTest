<?php
//dd(Input::all());
//dd($input);
$customer_id;
$gift_vouchers_parameters_id;

$gift_vouchers_parameters		= (GiftVouchersParameter::find(Input::get('gift_vouchers_parameters_id')));
$MerchantBusinessName 			= (Merchant::find($gift_vouchers_parameters->MerchantID)->business_name);
$gift_vouchers_parameters_Title	= ($gift_vouchers_parameters->Title);
/*
//
$item->customer_name 	= (Customer::find($item->customer_id)->name);
$item->qr_code_str 		= link_to_route($route.'.show', $item->qr_code, array($item->id));
$item->is_valid_str 	= ($item->is_valid 		== 1) ? 'Yes' : 'No';
$item->is_validated_str	= ($item->is_validated	== 1) ? 'Yes' : 'No';
$item->is_expired_str	= ($item->is_expired	== 1) ? 'Yes' : 'No';
//
$edit_btn 	 = link_to_route($route.'.edit', 'Edit', array($item->id), array('class' => 'btn btn-info'));
//
$delete_btn  = Form::open(array('method' => 'DELETE', 'route' => array($route.'.destroy', $item->id)));
$delete_btn .= Form::submit('Delete', array('class' => 'btn btn-danger', 'onclick'=>'return ConfirmDelete()'));
$delete_btn .= Form::close();
*/
?>