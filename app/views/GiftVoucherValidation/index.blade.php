@extends('layouts.main')
@section('main')
<div class="forms-back">
<div align="right">{{ link_to_route($RouteName.'.create', 'Add new gift voucher log', array('giftvoucher_id'=>'44')) }}</div>
<h2>Gift Voucher Log</h2>

@if (!$group->count())
    There are no gift voucher log.
@else
	<table class="table table-hover list-table">
    <thead>
    	<tr>
	        <th>Date</th>
	        <th>Value</th>
	        <th>Balance</th>
	        <th>Log</th>
	        <!--th colspan="2"></th-->
        </tr>
    </thead>
    <tbody>
    @foreach ($group as $item)
		<?php include(app_path().'/views/GiftVoucherValidation/_inc_item.php');?>
        <tr>
          	<td>{{ $item->date_str		}}</td>
          	<td>{{ $item->value_str		}}</td>
          	<td>{{ $item->balance_str	}}</td>
          	<td>{{ $item->log			}}</td>
        </tr>
    @endforeach
    </tbody>
    </table>
    {{ $group->links() }}
	</div>
@endif
@stop