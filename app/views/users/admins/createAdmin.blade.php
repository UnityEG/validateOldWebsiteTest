@extends('layouts.main')
@section('main')
<style>
	input[type=text]:not(.filter),input[type=email],
	input[type=password],input[type=date],
	input[type=search],input[type=tel],input[type=phone]
	{
		width:100%;
	}
	.ui-autocomplete-input{
		width:90% !important;
		height: 40px !important;
	}
	.ui-button-icon-only{
		width:9% !important;
	}
	.custom-combobox a{
		height:40px !important;
	}
	.custom-form-group{
		margin-bottom:4px !important;
	}
	.form-group{
	margin-bottom: -5px;
	}
</style>
<div class="forms-back">
{{Form::open(['route'=>'admin.store', 'role'=>'form'])}}
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
<div class="form-group">
    {{Form::label('email', 'Email: ')}}
    {{Form::email('email', null, array('class'=>'form-control'))}}
    {{$errors->first('email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password confirmation')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
<div class="form-group">
{{Form::label('first_name', 'First Name: ')}}
{{Form::text('first_name', null, array('class'=>'form-control'))}}
{{$errors->first('first_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
{{Form::label('last_name', 'Last Name: ')}}
{{Form::text('last_name', null, array('class'=>'form-control'))}}
{{$errors->first('last_name', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
</div>

@if(Auth::user()->hasRule('assign_rules'))

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['default_assigned_rules'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

@endif

@if(Auth::user()->hasRule('user_activate'))
<div class='form-group'>
    {{Form::label('active', 'Active User: ')}}
    {{Form::checkbox('active', true, true);}}
    {{$errors->first('active', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user()->hasRule('admin_activate'))
<div class="form-group">
    {{Form::label('active_admin', 'Active Admin: ')}}
    {{Form::checkbox('active_admin', true, true)}}
    {{$errors->first('active_admin', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

<div class="form-group">
    {{Form::submit('Create Admin', array('class'=>'btn btn-primary'))}}
    {{link_to('admin/', 'Cancel', array('class'=>'btn btn-danger'))}}
</div>

{{Form::close()}}
</div>
<script src="{{{asset('js/dual-list-box.min.js')}}}"></script>

<script>
$("#assign_rules").DualListBox();
</script>

@stop

