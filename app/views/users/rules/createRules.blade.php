@extends('layouts.main')

@section('main')

<div class="forms-back">
{{Form::open(array('route'=>'rules.store', 'method'=>'post', 'role'=>'form'))}}

<div class="form-group">
{{Form::label('rule_name', 'Rule Name: ')}}
{{Form::text('rule_name', null, array('class'=>'form-control'))}}
{{$errors->first('rule_name')}}
</div>

<div class="form-group">
    {{Form::submit('Create Rule', array('class'=>'btn btn-primary'))}}
    {{link_to_route('rules.index', 'Cancel', null, array('class'=>'btn btn-danger'))}}
</div>
{{Form::close()}}
</div>
@stop