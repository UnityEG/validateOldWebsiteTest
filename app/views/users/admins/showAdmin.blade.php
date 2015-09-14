@extends('layouts.main')
@section('main')
<div class="forms-back">
<table class="table table-hover list-table">
	<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
		<th>Actions</th>
    </tr>
	</thead>
	<tbody>
    <tr>
        <td>{{{$user_info->first_name.' '.$user_info->last_name}}}</td>
       <td>{{link_to($user->user_type, $user->user_type)}}</td>
		<td>
		<a href="{{route($user->user_type.'.edit', $user_info->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(['url'=>route($user->user_type.'.destroy', $user_info->id), 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("are you sure you want to delete '.$user_info->name.'?")'))}}
            {{Form::close()}}
        </td>
    </tr>
	</tbody>
</table>
</div>
@stop
