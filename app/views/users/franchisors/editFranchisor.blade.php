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
		width:90%;
		height: 40px;
	}
	.ui-button-icon-only{
		width:9%;
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
<link rel="stylesheet" href="{{{asset('css/chosen.min.css')}}}">
<div class="forms-back">
{{Form::model($item, array('route'=>array('franchisor.update', $item->id), 'method'=>'put', 'role'=>'form'))}}
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
<div class="form-group">
    {{Form::label('email', 'E-mail: ')}}
    {{Form::email('email', $item_info_user->email, array('class'=>'form-control'))}}
    {{$errors->first('email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password: ')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password Confirmation: ')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
<div class="form-group">
    {{Form::label('franchisor_name', 'Franchisor Name: ')}}
    {{Form::text('franchisor_name', null, array('class'=>'form-control'))}}
    {{$errors->first('franchisor_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('contact', 'Contact Name: ')}}
    {{Form::text('contact', null, array('class'=>'form-control'))}}
    {{$errors->first('contact', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('phone', 'Phone: ')}}
    {{Form::input('tel', 'phone', null, array('class'=>'form-control'))}}
    {{$errors->first('phone', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
</div>

@if(Auth::user()->hasRule('assign_rules'))

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $item_rule_ids, array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('reset_rules', 'Reset Rules:')}}
    {{Form::checkbox('reset_rules', true, false)}}
    {{$errors->first('reset_rules', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user()->hasRule('user_activate'))
<div class="form-group">
    {{Form::label('active', 'Active User: ')}}
    <?php $checked = (isset($item_info_user->active) && $item_info_user->active == TRUE)? TRUE:FALSE; ?>
    {{Form::checkbox('active', true, $checked)}}
    {{$errors->first('active', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user()->hasRule('franchisor_activate'))
<div class="form-group">
    {{Form::label('active_franchisor', 'Active Franchisor: ')}}
    <?php $checked = (isset($item->active_franchisor)&&$item->active_franchisor == TRUE)?TRUE:FALSE; ?>
    {{Form::checkbox('active_franchisor', true, $checked)}}
    {{$errors->first('active_franchisor', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

<div class="form-group">
    {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
    {{link_to_route('franchisor.show', 'Cancel', array($item->id), array('class'=>'btn btn-danger'))}}
</div>

{{Form::close()}}
</div>
<script src="{{{asset('js/dual-list-box.min.js')}}}"></script>

<script>
$("#assign_rules").DualListBox();
</script>

@stop