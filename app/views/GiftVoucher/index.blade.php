<?php $route = 'GiftVoucher';?>

@extends('layouts.main')
@section('main')
<div class="forms-back">
<!--div align="right">{{ link_to_route($route.'.create', 'Add new gift voucher', array('customer_id'=>'3', 'gift_vouchers_parameters_id'=>'2')) }}</div-->
<h2>Purchased Gift Vouchers</h2>

@if (!$group->count())
    There are no gift voucher.
@else
	<table class="table table-hover list-table">
    <thead>
    	<tr>
        	<th>Voucher Code</th>
	        <th>Status</th>
	        <th>Merchant</th>
	        <th>Expiry Date</th>
	        <th>Voucher Value</th>
	        <th>Voucher Balance</th>
	        <!--th colspan="2"></th-->
        </tr>
    </thead>
    <tbody>
    @foreach ($group as $item)
		<?php include(app_path().'/views/GiftVoucher/_inc_item.php');?>
        <tr>
        	<td>{{ $item->qr_code_str					}}</td>
          	<td>{{ $item->status_str					}}</td>
        	<td>{{ $item->MerchantBusinessName 			}}</td>
          	<td>{{ $item->expiry_date					}}</td>
          	<td>{{ "$" . number_format($item->voucher_value, 2, '.', ',')	}}</td>
          	<td>{{ "$" . number_format($item->voucher_balance, 2, '.', ',')	}}</td>
            <!--td>{{ $edit_btn 							}}</td>
            <td>{{ $delete_btn 							}}</td-->
        </tr>
    @endforeach
    </tbody>
    </table>
    {{ $group->links() }}
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
	</script>
@endif
@stop