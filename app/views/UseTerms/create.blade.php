@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<div align="right">{{ link_to_route('UseTerms.index', 'List all terms of use') }}</div>
<h1>Create Term of Use</h1>
{{ Form::open(array('route' => 'UseTerms.store', 'role' => 'form')) }}
	<div class="form-group">
		{{ Form::label('name', 'Term of use:') }}
		{{ Form::text('name', null, array('class'=>'form-control')) }}
	</div>
	{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
{{ Form::close() }}
</div>
@stop