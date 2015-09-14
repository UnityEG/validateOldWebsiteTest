@extends('layouts.main')
@section('main')
<div class="forms-back">
<h2>All users</h2>
<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
		<th>Actions</th>
    </tr>
</thead> 
	<tbody>  
    <?php
    foreach($users as $user):
    $outside_info = $user->user_type;
    $user_info    = $user->$outside_info()->first();
    if($outside_info == null || $user_info == null){
        continue;
    }
    ?>

    <tr>
        <td><a href="{{route($user->user_type.'.show', $user_info->id)}}">{{$user_info->first_name.' '.$user_info->last_name}}</a></td>
        
        @if($user->user_type == 'owner')
        <td>Owner</td>
        @else
        <td>{{link_to($user->user_type, $user->user_type)}}</td>
        @endif
		<td>
		<a href="{{route($user->user_type.'.edit', $user_info->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(['url'=>$user->user_type.'/'.$user_info->id, 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("Are you sure you want to delete '.$user->email.' ?")'))}}
            {{Form::close()}}
        </td>
    </tr>
	
    <?php endforeach;?>
  </tbody> 
</table>
{{$users->links()}}
</div>
@stop