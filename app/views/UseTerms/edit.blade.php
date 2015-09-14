@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<div align="right">{{ link_to_route('UseTerms.index', 'List all terms of use') }}</div>
<h1>Edit Term of Use</h1>
{{ Form::model($UseTerm, array('method' => 'PATCH', 'route' => array('UseTerms.update', $UseTerm->id))) }}
	<div class="form-group">
		{{ Form::label('name', 'Term of use:') }}
		{{ Form::text('name', null, array('class'=>'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
		{{ link_to_route('UseTerms.show', 'Cancel', $UseTerm->id, array('class' => 'btn')) }}
	</div>

{{ Form::close() }}
</div>
@stop