@extends('layouts.main')

@section('main')
<div class="forms-back">
<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Franchisor Name</th>
        <th>Contact Name</th>
        <th>Phone</th>
        <th>Type</th>
        <th>Merchants</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>    
    <tr>
        <td>{{{$item->franchisor_name}}}</td>
        
        <td>
            {{{$item->contact}}}
        </td>
        
        <td>{{{$item->phone}}}</td>
        
        <td>
            {{link_to_route('franchisor.index', $item_info_user->user_type)}}
        </td>
        
        <td>
            <ul>
                @foreach($item_info_merchant as $merchant)
                <li>{{link_to_route('merchant.show', ucfirst($merchant->first_name).' '.$merchant->last_name, array($merchant->id))}}</li>
                @endforeach
            </ul>
        </td>

		<td>
		<a href="{{route('franchisor.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(array('route'=>array('franchisor.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->first_name?')"))}}
            {{Form::close()}}
        </td>
    </tr>
	</tbody>
</table>
</div>
@stop