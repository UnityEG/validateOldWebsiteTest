<?php

//$gift_vouchers_parameters_item = (GiftVouchersParameter::find($item->gift_vouchers_parameters_id));
//$item->MerchantBusinessName 		= (Merchant::find($gift_vouchers_parameters_item->MerchantID)->business_name);
$item->MerchantBusinessName = $item->parameter->merchant->business_name;
$item->gift_vouchers_parameters_Title = $item->parameter->Title;
$item->NoOfUses_str = '';
$item->NoOfUsesLeft_str = '';
if ($item->parameter->SingleUse == 1) {
    $item->NoOfUses_str = 'Single Use';
    $item->NoOfUsesLeft_str = 1 - $item->used_times;
} elseif ($item->parameter->NoOfUses == null) {
    $item->NoOfUses_str = 'Unlimited';
    $item->NoOfUsesLeft_str = 'Unlimited';
} else {
    $item->NoOfUses_str = $item->parameter->NoOfUses;
    $item->NoOfUsesLeft_str = $item->parameter->NoOfUses - $item->used_times;
}
//
$item->customer_name = $item->customer->getName();
$item->qr_code_str = link_to_route($route . '.show', substr($item->qr_code, 0, 3) . '&nbsp;-&nbsp;' . substr($item->qr_code, 3, 3) . '&nbsp;-&nbsp;' . substr($item->qr_code, 6, 3), array($item->id));
$item->is_valid_str = ($item->is_valid == 1) ? 'Yes' : 'No';
$item->is_validated_str = ($item->is_validated == 1) ? 'Yes' : 'No';
$item->is_expired_str = ($item->is_expired == 1) ? 'Yes' : 'No';
//
/*
$date_array = explode("-", $item->delivery_date);
$item->delivery_date = $date_array[2] . '/' . $date_array[1] . '/' . $date_array[0];
$date_array = explode("-", $item->expiry_date);
$item->expiry_date = $date_array[2] . '/' . $date_array[1] . '/' . $date_array[0];
*/
//
$status_btn = '';
//
//dd($item->parameter->SingleUse);
switch ($item->status) {
    case 0:
        $item->status_str = 'Invalid';
        break;
    case 1:
        $item->status_str = 'Valid';
        //
        if ($item->parameter->SingleUse == 1) {
            $msg = "This voucher will be validated and cannot be recovered. Are you sure?";
            $status_btn = Form::open(array('method' => 'POST', 'route' => array($route . '.status', $item->id)));
            $status_btn .= Form::hidden('status', 2);
            $status_btn .= Form::hidden('voucher_balance', 0);
            $status_btn .= Form::hidden('used_times', 1);
            $status_btn .= Form::submit('Validate', array('class' => 'btn btn-info', 'onclick' => 'return ConfirmMsg("' . $msg . '")'));
            $status_btn .= Form::close();
        } else {
            $msg = "This voucher will be validated and cannot be recovered. Are you sure?";
            $status_btn = Form::open(array('method' => 'POST', 'route' => array('GiftVoucherValidation.create', 'giftvoucher_id' => $item->id)));
            $status_btn .= Form::submit('Validate', array('class' => 'btn btn-info'));
            $status_btn .= Form::close();
        }
        break;
    case 2:
        $item->status_str = 'Validated';
        break;
    default:
        $item->status_str = 'unhandled';
} // switch
//
$edit_btn = link_to_route($route . '.edit', 'Edit', array($item->id), array('class' => 'btn btn-info'));
//
$delete_btn = Form::open(array('method' => 'DELETE', 'route' => array($route . '.destroy', $item->id)));
$delete_btn .= Form::submit('Delete', array('class' => 'btn btn-danger', 'onclick' => 'return ConfirmDelete()'));
$delete_btn .= Form::close();
//
?>