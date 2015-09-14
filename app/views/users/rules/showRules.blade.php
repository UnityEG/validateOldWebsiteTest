@extends('layouts.main')

@section('main')
<div class="forms-back">
<!--//todo add curcomb nav-->
<h2>{{link_to_route('rules.index', 'Rule')}} {{$data['rule']->rule_name}}</h2>

<table class="table table-hover list-table">
<thead>
    <tr>
        <th class="text-center">Rule Name</th>
        <th class="text-center">Users</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="text-center">
            {{$data['rule']->rule_name}}
        </td>
        
        <td class="text-center">
            {{$data['users_list']}}
        </td>
        
        <td class="text-center">
		<a href="{{route('rules.edit', $data['rule']->id)}}" class="btn btn-primary">Assign to User <span class="glyphicon glyphicon-share-alt"></span></a>

        @if($data['delete_rule'] == true)
            {{Form::open(array('route'=>array('rules.destroy', $data['rule']->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete ".$data['rule']->rule_name." ?')"))}}
			{{Form::close()}}
		@endif
        </td>
        

    </tr>
</tbody>
</table>
</div>
@stop