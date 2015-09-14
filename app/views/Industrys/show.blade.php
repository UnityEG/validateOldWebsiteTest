@extends('layouts.main')
@section('main')
<div class="forms-back">
@if (is_null($Industry))
    There are no terms of use.
@else
<div align="right">{{ link_to_route('Industrys.index', 'List all Industries') }}</div>
<h2>Industry</h2>

<table class="table table-hover list-table">
	<tr>
		<th>Industry</th>
		<td>{{ $Industry->industry }}</td>
	</tr>
</table>

<div class="form-group">
{{ Form::open(array('method' => 'DELETE', 'route' => array('Industrys.destroy', $Industry->id))) }}
	<a href="{{route('Industrys.edit', $Industry->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
	{{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return ConfirmDelete()'))}}
{{ Form::close() }}

</div>

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