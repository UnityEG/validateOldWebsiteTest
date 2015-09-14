@extends('layouts.main')
@section('main')
<div class="forms-back">
<p>{{link_to_route('merchant_manager.create', 'Create new Merchant Manager')}}</p>

<h3>{{$item->first_name.' '.$item->last_name}}'s profile</h3>

<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Merchant</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>{{ucfirst($item->first_name).' '.$item->last_name}}</td>
        <td>{{link_to_route('merchant_manager.index',$item_info_user->user_type)}}</td>
         
        <td>
            @unless(empty($item_info_merchant) || null == $item_info_merchant)
            {{link_to_route('merchant.show', ucfirst($item_info_merchant->first_name).' '.$item_info_merchant->last_name, array($item_info_merchant->id))}}
            @endunless
        </td>
		<td>
		<a href="{{route('merchant_manager.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(array('route'=>array('merchant_manager.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->first_name $item->last_name?')"))}}
            {{Form::close()}}
        </td>
    </tr>
</tbody>
</table>
</div>
@stop
