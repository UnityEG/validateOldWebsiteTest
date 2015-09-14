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
</style>
<div class="forms-back">
    <p><a class="btn btn-success" href="{{route('merchant.create')}}"><span class="glyphicon glyphicon-user"></span> Create Merchant</a></p>
    <h2>Merchants</h2>

    <div class="form-group">
        {{Form::input('search', 'searchKeys', null, array('class'=>'form-control input-lg', 'placeholder'=>'Search For Merchant'))}}
        <div id="searchResult"></div>
    </div>

    <table class="table table-hover list-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Photo</th>
                <th>Logo</th>
                <th>Type</th>
                <th>Franchisor</th>
                <th>Suppliers</th>
                <th>Merchant Managers</th>
                <th>Actions</th>
            </tr>
        </thead>  
        <tbody> 
            <?php
            foreach ( $group as $item ):
                $item_info_from_user_table = $item->user()->first();
                $item_info_franchisor      = $item->franchisor()->first();
                $active_logo               = $item_info_from_user_table->userPic()->where( 'type', '=', 'logo' )->where( 'active_pic', '=', 1 )->first( array( 'pic', 'extension' ) );
                $active_photo              = $item_info_from_user_table->userPic()->where( 'type', '=', 'photo' )->where( 'active_pic', '=', '1' )->first( array( 'pic', 'extension' ) );

//    building suppliers list
                $item_info_supplier = $item->supplier;
                $suppliers_list     = "<ul>";
                foreach ( $item_info_supplier as $supplier ) {
                    $suppliers_list .= "<li>";
                    $suppliers_list .= link_to_route( 'supplier.show', $supplier->first_name, array( $supplier->id ) );
                    $suppliers_list .= "</li>";
                }
                $suppliers_list .= "</ul>";

//    building merchant manager list
                $item_info_merchant_manager = $item->merchantManagers;
                $merchant_manager_list      = '<ul>';
                foreach ( $item_info_merchant_manager as $merchant_manager ) {
                    $merchant_manager_list .= "<li>";
                    $merchant_manager_list .= link_to_route( 'merchant_manager.show', $merchant_manager->first_name . ' ' . $merchant_manager->last_name, array( $merchant_manager->id ) );
                    $merchant_manager_list .="</li>";
                }
                $merchant_manager_list .= "</ul>";
                ?>
                <tr>
                    <td>
                        <a href="{{route('merchant.show', $item->id)}}">{{$item->first_name.' '.$item->last_name}}</a>
                    </td>

                    <td>
                        @if(is_object($active_photo))
                        <img src="{{{$merchant_photo_path.'/'.$active_photo->pic.'.'.$active_photo->extension}}}" style="width:50px;height: 50px;">
                        @endif
                    </td>

                    <td>
                        @if(is_object($active_logo))
                        <img src="{{{$merchant_logo_path.'/'.$active_logo->pic.'.'.$active_logo->extension}}}" style="width: 50px;height: 50px;">
                        @endif
                    </td>

                    <td>{{{$item_info_from_user_table->user_type}}}</td>

                    <td>
                        @if(isset($item_info_franchisor) && !empty($item_info_franchisor))
                        {{link_to_route('franchisor.show', $item_info_franchisor->franchisor_name, array($item_info_franchisor->id))}}
                        @endif
                    </td>

                    <td>
                        @if(isset($suppliers_list) && !empty($suppliers_list))
                        {{$suppliers_list}}
                        @endif
                    </td>

                    <td>
                        @if(isset($merchant_manager_list) && !empty($merchant_manager_list))
                        {{$merchant_manager_list}}
                        @endif
                    </td>
                    
                    <td>
                        {{link_to_route('reports.merchant', 'Reports', array($item->id))}}
                    </td>

                    <td>
                        <a href="{{route('merchant.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>

                        {{Form::open(['url'=>'merchant/'.$item->id, 'method'=>'delete'])}}
                        {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger ', 'onclick'=>'return confirm("Are you sure you want to delete '.$item->first_name.' ?")'))}}
                        {{Form::close()}}
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    {{$group->links()}}
</div>

<script src="{{asset('js/searchUsers.js')}}"></script>
<script>

    jQuery("document").ready(function () {
        searchProcess.url = "{{route('merchant.index')}}";
        searchProcess.searchResult();

    });//jQuery("document").ready(function()

</script>
@stop
