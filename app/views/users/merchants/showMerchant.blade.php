@extends('layouts.main')
@section('main')
<div class="forms-back">
<p>{{link_to_route('merchant_manager.index', 'Merchant Managers', null, array('role'=>'link'))}}</p>
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
    <tr>
        <td>{{{$item->first_name.' '.$item->last_name}}}</td>
        
        <td>
            <img src="{{{$data['item_photo']}}}" style="width:50px;height: 50px;">
        </td>
        
        <td>
            <img src="{{{$data['item_logo']}}}" style="width:50px;height:50px;">
        </td>
        
       <td>
           {{link_to($item_information_from_user_table->user_type.'/', $item_information_from_user_table->user_type)}}
       </td>
       
       <td>
           @if(isset($item_info_franchisor) && !empty($item_info_franchisor))
           {{link_to_route('franchisor.show', $item_info_franchisor->franchisor_name, array($item_info_franchisor->id))}}
           @endif
       </td>
       
       <td>
           {{$suppliers_list}}
       </td>
       
       <td>
           {{$merchant_managers_list}}
       </td>
       
		<td>
		<a href="{{route($item_information_from_user_table->user_type.'.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
            {{Form::open(['url'=>route($item_information_from_user_table->user_type.'.destroy', $item->id), 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("are you sure you want to delete '.$item->first_name.'?")'))}}
            {{Form::close()}}
        </td>
    </tr>
</tbody>
</table>
</div>
@stop
