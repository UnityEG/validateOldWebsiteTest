<?php extract($data); ?>
@extends('layouts.main')
@section('main')

<style>
    #searchResult{
        display: none;
        background-color: #CFFCC7;
        border: 1px solid #aaaaaa;
        position: absolute;
        top: 40px;
        width: 100%;
        z-index: 1000;
    }
    #searchResult div{
        margin-top: 10px;
        font-family: Verdana,Arial,sans-serif;
        font-size: 1.1em;
        margin: 0;
        padding: 3px 1em 3px .4em;
        cursor: pointer;
        min-height: 0;
        line-height: 1.7;
    }
    @media(min-width:1000px){
        table tr td{
            min-width:70px;
        }
    }
</style>

<div class="forms-back">
    <!--<div align="right">{{ link_to_route('GiftVouchersParameters.create', 'Create Gift Voucher') }}</div>-->
    <h2>Gift Vouchers</h2>

    <div class="form-group">
        <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Enter Merchant name">&nbsp;
        @if(isset($merchant_business_name))
        <span class="alert alert-danger">
            Filter: {{{$merchant_business_name}}}
            <a href="{{route('GiftVouchersParameters.index')}}"><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
        </span>
        @endif
        <div id="searchResult"></div>
    </div>

    @if (!$GiftVouchersParameters->count())
    There are no gift voucher parameters
    @else
    <table class="table table-hover list-table">
        <thead>
            <tr>
                <th>Purchase</th>
                <th>Merchant</th>
                <th>Voucher Title</th>
                <th>Valid For</th>
                <th>Quantity</th>
                <th>Actions</th>
<!--	        <th>Single Use</th>
        <th>Number of Uses</th>
        <th>Limited Quantity</th>
        <th>Min Val</th>
        <th>Max Val</th>
        <th>Terms of Use</th>
        <th colspan="2"></th>-->
            </tr>
        </thead>
        <tbody>
            @foreach ($GiftVouchersParameters as $GiftVouchersParameter)
            <?php $MerchantBusinessName = (Merchant::find($GiftVouchersParameter->MerchantID)->business_name); ?>
            <?php $SingleUse = ($GiftVouchersParameter->SingleUse == 1) ? 'Yes' : 'No'; ?>
            <?php $NoOfUses = ($GiftVouchersParameter->NoOfUses != '') ? $GiftVouchersParameter->NoOfUses : 'Unlimited'; ?>
            <?php //$Expires   		= ($GiftVouchersParameter->Expires   		== 1) ? 'Yes' : 'No'; ?>
            <?php $LimitedQuantity = ($GiftVouchersParameter->LimitedQuantity == 1) ? 'Yes' : 'No'; ?>
            <?php $Quantity = ($GiftVouchersParameter->LimitedQuantity == 1 ) ? $GiftVouchersParameter->Quantity : 'Unlimited'; ?>
            <?php
            switch ($GiftVouchersParameter->ValidForUnits) {
                /*
                  case null:
                  $ValidForUnits = 'Non expired';
                  break;
                 */
                case 'd':
                    $ValidForUnits = 'day(s)';
                    break;
                case 'w':
                    $ValidForUnits = 'week(s)';
                    break;
                case 'm':
                    $ValidForUnits = 'month(s)';
                    break;
                default:
                    $ValidForUnits = 'Unkown unit!';
            } // switch
            ?>
            <?php $purchase_btn = link_to_route('GiftVoucher.create', 'Purchase', array($GiftVouchersParameter->id), array('class' => 'btn btn-info')); ?>

            <tr>

                <td>{{ $purchase_btn 							}}</td>
                <td>{{ $MerchantBusinessName 					}}</td>
                <td>{{ link_to_route('GiftVouchersParameters.show', $GiftVouchersParameter->Title, array($GiftVouchersParameter->id)) }}</td>
                <td>{{ $GiftVouchersParameter->ValidFor	. ' ' . $ValidForUnits}}</td>
                <td>{{ $Quantity			}}</td>
<!--          	<td>{{ $SingleUse 								}}</td>
        <td>{{ $NoOfUses								}}</td>
        <td>{{ $LimitedQuantity						}}</td>
        <td>{{ $GiftVouchersParameter->MinVal 			}}</td>
        <td>{{ $GiftVouchersParameter->MaxVal 			}}</td>
        <td>{{ $GiftVouchersParameter->TermsOfUse		}}</td>-->

                <td>
                    <a href="{{route('GiftVouchersParameters.edit', $GiftVouchersParameter->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
                    {{ Form::open(array('method' => 'DELETE', 'route' => array('GiftVouchersParameters.destroy', $GiftVouchersParameter->id))) }}
                    {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return ConfirmDelete()'))}}
                    {{Form::close()}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $GiftVouchersParameters->links() }}

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
</div>

<script src="{{asset('js/searchUsers.js')}}"></script>
<script>

        jQuery("document").ready(function () {
            searchProcess.url = "{{route('GiftVouchersParameters.ajaxSearch')}}";
            searchProcess.searchResult();

        });//jQuery("document").ready(function()

</script>

@stop