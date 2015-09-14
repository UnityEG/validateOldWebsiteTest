@extends('layouts.main')
@section('main')
<div class="forms-back">
@if (is_null($UseTerm))
    There are no terms of use.
@else
<div align="right">{{ link_to_route('UseTerms.index', 'List all terms of use') }}</div>
<h2>Term of Use</h2>

<table class="table table-hover list-table">
	<tr>
		<th>Term of use</th>
		<td>{{ $UseTerm->name }}</td>
	</tr>
</table>

<div class="form-group">
{{ Form::open(array('method' => 'DELETE', 'route' => array('UseTerms.destroy', $UseTerm->id))) }}
	<a href="{{route('UseTerms.edit', $UseTerm->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
	{{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return ConfirmDelete()'))}}
{{ Form::close() }}

</div>
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
@stop