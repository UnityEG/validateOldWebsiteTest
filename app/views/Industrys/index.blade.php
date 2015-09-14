@extends('layouts.main')
@section('main')
<div class="forms-back">
<p><a class="btn btn-success" href="{{route('Industrys.create')}}"><span class="glyphicon glyphicon-plus"></span> Add New Industry</a></p>

<h2>All Industries</h2>

@if (!$Industrys->count())
    There are no Industries
@else
	<table class="table table-hover list-table">
    <thead>
    	<tr>
        	<th>Industry</th>
	        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($Industrys as $Industry)
        <tr>
        	<td>{{ link_to_route('Industrys.show', $Industry->industry, array($Industry->id)) }}</td>

		<td>
		<a href="{{route('Industrys.edit', $Industry->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
			{{Form::open(array('method' => 'DELETE', 'route' => array('Industrys.destroy', $Industry->id))) }}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return ConfirmDelete()'))}}
            {{Form::close()}}
        </td>
        </tr>
    @endforeach
    </tbody>
    </table>
   

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
@stop