<?php extract($data); ?>
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
        <td>
            {{{ucfirst($data['item']->first_name.' '.$data['item']->last_name)}}}
        </td>
       <td>
           @if(isset($item_info_user->user_type))
           {{link_to($data['item_info_user']->user_type.'/', $data['item_info_user']->user_type)}}
           @endif
       </td>		
        <td>
            <a href="{{route('customer.edit', $data['item']->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(['route'=>array('customer.destroy', $data['item']->id), 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("are you sure you want to delete '.$data['item']->name.'?")'))}}
            {{Form::close()}}
        </td>
    </tr>
<tbody>
</table>
</div>
@stop
