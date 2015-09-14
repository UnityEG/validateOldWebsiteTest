@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<div align="right">{{ link_to_route('Industrys.index', 'List all Industries') }}</div>
<h1>Edit Industry</h1>
{{ Form::model($Industry, array('method' => 'PATCH', 'route' => array('Industrys.update', $Industry->id))) }}
	<div class="form-group">
		{{ Form::label('industry', 'Industry:') }}
		{{ Form::text('industry', null, array('class'=>'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
		{{ link_to_route('Industrys.show', 'Cancel', $Industry->id, array('class' => 'btn')) }}
	</div>

{{ Form::close() }}
</div>
@stop