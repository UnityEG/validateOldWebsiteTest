@extends('layouts.main')

@section('main')
<div class="forms-back">
<p>{{$item->first_name.' '.$item->last_name}}'s profile</p>

<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Merchants</th>
        <th>Actions</th>
    </tr>
</thead> 
<tbody>  
    <tr>
        <td>{{ucfirst($item->first_name).' '.$item->last_name}}</td>
        <td>
            {{link_to_route('supplier.index', $item_info_user->user_type)}}
        </td>
        
        <td>{{$merchants_list}}</td>
        
		<td>
		<a href="{{route('supplier.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
            {{Form::open(array('route'=>array('supplier.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete ".$item->first_name." ".$item->last_name."?')"))}}
            {{Form::close()}}
        </td>
    </tr>
</tbody>
</table>
</div>
@stop