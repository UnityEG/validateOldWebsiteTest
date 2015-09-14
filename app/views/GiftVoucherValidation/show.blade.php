<?php $route = 'GiftVoucher';?>
@extends('layouts.main')
@section('main')
<div class="forms-back">
@if (is_null($item))
    There is no gift voucher.
@else
<div align="right">{{ link_to_route($route.'.index', 'List all') }}</div>
<h1>Gift Voucher</h1>
<?php include(app_path().'/views/GiftVoucher/_inc_item.php');?>

<table class="table table-hover list-table">
    	<tr>
        	<th>Voucher Code</th>
        	<th>{{ substr($item->qr_code, 0, 3) . ' - ' . substr($item->qr_code, 3, 3) . ' - ' . substr($item->qr_code, 6, 3) }}</th>
        </tr>
    	<tr>
        	<th>Status</th>
        	<th>{{ $item->status_str}}{{$status_btn}}</th>
        </tr>
    	<tr>
	        <th>Merchant</th>
        	<td>{{ $item->MerchantBusinessName 			}}</td>
        </tr>
    	<tr>
	        <th>Gift Voucher Title</th>
        	<td>{{ $item->gift_vouchers_parameters_Title}}</td>
        </tr>
    	<tr>
	        <th>Customer</th>
        	<td>{{ $item->customer_name	 				}}</td>
        </tr>
    	<tr>
	        <th>Delivery Date</th>
          	<td>{{ $item->delivery_date					}}</td>
        </tr>
    	<tr>
	        <th>Expiry Date</th>
          	<td>{{ $item->expiry_date					}}</td>
        </tr>
    	<tr>
	        <th>Voucher Value</th>
          	<td>{{ "$" . number_format($item->voucher_value, 2, '.', ',')	}}</td>
        </tr>
    	<tr>
	        <th>Voucher Balance</th>
          	<td>{{ "$" . number_format($item->voucher_balance, 2, '.', ',')	}}</td>
        </tr>
    	<tr>
	        <th>Payment Status</th>
          	<td>{{ $item->payment_status				}}</td>
        </tr>
    	<tr>
	        <th>Validation Date</th>
          	<td>{{ $item->validation_date				}}</td>
        </tr>
    	<tr>
	        <th>Recipient Email</th>
          	<td>{{ $item->recipient_email				}}</td>
        </tr>
    	<tr>
	        <th>Message</th>
          	<td>{{ $item->message						}}</td>
        </tr>
    </table>
    <!--table>
    	<tr>
            <td style="padding:5px;">{{ $edit_btn	}}</td>
            <td style="padding:5px;">{{ $delete_btn	}}</td>
        </tr>
    </table-->
</div>
<script>
function ConfirmDelete() {
    var r = confirm("This item will be permanently deleted and cannot be recovered. Are you sure?");
    if (r == true) {
        //txt = "You pressed OK!";
    } else {
        //txt = "You pressed Cancel!";
        return false;
    }
}

function ConfirmMsg(msg) {
    var r = confirm(msg);
    if (r == true) {
        //txt = "You pressed OK!";
    } else {
        //txt = "You pressed Cancel!";
        return false;
    }
}
</script>
@endif
@stop