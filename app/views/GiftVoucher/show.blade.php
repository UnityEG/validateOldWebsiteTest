<?php $route = 'GiftVoucher';?>
@extends('layouts.main')
@section('main')
<div class="forms-back">
@if (is_null($item))
    There is no gift voucher.
@else
<h2>Gift Voucher</h2>
<?php include(app_path().'/views/GiftVoucher/_inc_item.php');?>

<div class="col-md-6">
<table class="table table-hover list-table">
    	<tr>
        	<th>Voucher Code</th>
        	<th>{{ substr($item->qr_code, 0, 3) . ' - ' . substr($item->qr_code, 3, 3) . ' - ' . substr($item->qr_code, 6, 3) }}</th>
        </tr>
    	<tr>
	        <th>Merchant</th>
        	<td>{{ $item->MerchantBusinessName 			}}</td>
        </tr>
    	<tr>
	        <th>Customer</th>
        	<td>{{ $item->customer_name	 				}}</td>
        </tr>
    	<tr>
	        <th>Purchase Date</th>
          	<td>{{ g::formatDateTime($item->created_at)					}}</td>
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
	        <th>Validate Guarantee</th>
          	<td></td>
        </tr>
    	<tr>
	        <th>Last Validation</th>
          	<td>{{ g::formatDateTime($item->validation_date)				}}</td>
        </tr>
    </table>
</div>
<div class="col-md-6">
<table class="table table-hover list-table">
    	<tr>
        	<th>Status</th>
        	<th>{{ $item->status_str}}</th>
        </tr>
    	<tr>
	        <th>Voucher Title</th>
        	<td>{{ $item->gift_vouchers_parameters_Title}}</td>
        </tr>
    	<tr>
	        <th>Recipient Email</th>
          	<td>{{ $item->recipient_email				}}</td>
        </tr>
    	<tr>
	        <th>Delivery Date</th>
          	<td>{{ g::formatDateTime($item->delivery_date)					}}</td>
        </tr>
    	<tr>
	        <th>Expiry Date</th>
          	<td>{{ g::formatDateTime($item->expiry_date)	}}</td>
        </tr>
    	<tr>
	        <th>Allowed Validations</th>
          	<td>{{ $item->NoOfUses_str	}}</td>
        </tr>
    	<tr>
	        <th>Validations Made</th>
          	<td>{{ $item->used_times	}}</td>
        </tr>
    	<tr>
	        <th>Validations Left</th>
          	<td>{{ $item->NoOfUsesLeft_str	}}</td>
        </tr>
    </table>
</div>

{{$status_btn}}




<h3>Voucher History</h3>

@if (!$group->count())
    There are no voucher logs.
@else
	<table class="table table-striped table-bordered">
    <thead>
    	<tr>
	        <th>Date</th>
	        <th>Value</th>
	        <th>Balance</th>
	        <th>Log</th>
	        <th>User</th>
	        <!--th colspan="2"></th-->
        </tr>
    </thead>
    <tbody>
    @foreach ($group as $item)
		<?php include(app_path().'/views/GiftVoucherValidation/_inc_item.php');?>
        <tr>
          	<td>{{ $item->date_str      }}</td>
          	<td>{{ $item->value_str     }}</td>
          	<td>{{ $item->balance_str   }}</td>
          	<td>{{ $item->log           }}</td>
          	<td>{{ $item->user_id       }}</td>
        </tr>
    @endforeach
    </tbody>
    </table>
@endif















    <!--table>
    	<tr>
            <td style="padding:5px;">{{ $edit_btn	}}</td>
            <td style="padding:5px;">{{ $delete_btn	}}</td>
        </tr>
    </table-->

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
</div>
@stop