@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<div align="right">{{ link_to_route('Industrys.index', 'List all Industries') }}</div>
<h1>Create Industry</h1>
{{ Form::open(array('route' => 'Industrys.store', 'role' => 'form')) }}
	<div class="form-group">
		{{ Form::label('industry', 'Industry:') }}
		{{ Form::text('industry', null, array('class'=>'form-control')) }}
	</div>
	{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
{{ Form::close() }}
</div>
@stop